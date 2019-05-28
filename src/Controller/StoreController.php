<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Sale;
use Symfony\Component\Validator\Constraints\DateTime;

class StoreController extends AbstractController
{

  /**
   * @Route("/tienda/{page}", name="store", requirements={"page"="\d+"})
   */
  public function showstore($page = 1)
  {

    $products = $this->getDoctrine()
      ->getRepository(Product::class)
      ->findAll();

      $limit = 18;
      $productsQuantity = count($products);
      $offset = ($page - 1) * $limit;
      $pagesQuantity = ceil($productsQuantity / $limit);

      $pagination_products = array_slice($products, $offset, $limit);

      return $this->render('frontend/store/index.html.twig', [
        'products' => $pagination_products,
        'pagesQuantity' => $pagesQuantity,
        'currentPage' => $page,
        'route' => 'store',
        'title' => 'Tienda'
      ]);
  }

    /**
     * @Route("/tienda/{categoryId}/{page}", name="storebycategory", requirements={"categoryId"="\d+"})
     */
    public function showstoreByCategory($page, $categoryId)
    {

         $category = $this->getDoctrine()
              ->getRepository(Category::class)
              ->find($categoryId);

          $products = $category->getProducts()->toArray();

          $limit = 18;
          $productsQuantity = count($products);
          $offset = ($page - 1) * $limit;
          $pagesQuantity = ceil($productsQuantity / $limit);

          $pagination_products = array_slice($products, $offset, $limit);

        return $this->render('frontend/store/index.html.twig', [
          'products' => $pagination_products,
          'pagesQuantity' => $pagesQuantity,
          'currentPage' => $page,
          'route' => 'storebycategory',
          'category' => $category,
          'title' => $category->getName()
        ]);
    }

    /**
     * @Route("/tienda/producto/{productId}", name="store-product", requirements={"productId"="\d+"})
     */
    public function product($productId){

      $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($productId);

      return $this->render('frontend/components/productmodal.html.twig', [
          'product' => $product,
          'title' => $product->getName()
      ]);
    }

    /**
     * @Route("/tienda/agregar-producto", name="store-add-product")
     */
    public function addproductToCart(Request $request){

      if($productId = $request->request->get('productId')){
          //make something curious, get some unbelieveable data

          $sessionCart = $request->getSession();
          $arrayOfProduct = $sessionCart->get('ProductIDs');
          if ($arrayOfProduct == null){
            $arrayOfProduct = [];
          }

          if($productId>0 && !in_Array($productId, $arrayOfProduct)){
             $arrayOfProduct[] = $productId;
          $sessionCart->set('ProductIDs', $arrayOfProduct);
          }

          $arrData = array("id" => $productId);
          return new JsonResponse($arrData);
      }


    }

    /**
     * @Route("/tienda/remove/{productid}", name="store-cart-remove-product")
     */
     public function removeproductfromCarte(Request $request, $productid){

       $sessionCart = $request->getSession();
       $arrayOfProduct = $sessionCart->get('ProductIDs');

       foreach (array_keys($arrayOfProduct, $productid) as $key) {
          unset($arrayOfProduct[$key]);
      }

     $sessionCart->set('ProductIDs', $arrayOfProduct);
     return $this->redirectToRoute('store-cart');

     }

    /**
     * @Route("/tienda/carro", name="store-cart")
     */
    public function cart(Request $request){

     $sessionCart = $request->getSession();
     $arrayOfProduct = $sessionCart->get('ProductIDs');
     $repository = $this->getDoctrine()->getRepository(Product::class);

      if($sessionCart->get('buyid')==""){
      $sessionCart->set('buyid', hexdec(uniqid()));
      }

      $currentbuyid =  $sessionCart->get('buyid');



     $productsinCart = $repository->findById($arrayOfProduct);

     $totalsumcart = 0;
      $gamesOrderDescription = "";
      foreach($productsinCart as $value){
        $totalsumcart = $totalsumcart + $value->getPrice();
      }

      return $this->render('frontend/store/cart.html.twig', [
        'products' => $productsinCart,
        'totalprice' => $totalsumcart,
        'title' => 'Carro'

      ]);
    }

    /**
     * @Route("/tienda/carro/limpiar", name="store-clear-cart")
     */
     public function clearCart(Request $request){

       $sessionCart = $request->getSession();
    $arrayOfProduct = $sessionCart->get('ProductIDs');
    $arrayOfProduct = array();
    $sessionCart->set('ProductIDs', $arrayOfProduct);
    $sessionCart->set('buyid', "");

        return $this->redirectToRoute('home');
     }

     /**
      * @Route("/tienda/carro/resume", name="store-resume-sale")
      */
      public function resumetotalCart(Request $request){

        if($total = $request->get('total')){

          $sessionCart = $request->getSession();
          $orderresume = json_encode($request->get('orderjson'), true);
          $currentbuyid =  $sessionCart->get('buyid');
          $name = $request->get('cname');
          $mail = $request->get('cmail');
          $phone = $request->get('cphone');


          /*foreach($orderresume as $item) { //foreach element in $arr
            //  $uses = $item['name']; //etc
          }*/

               $sessionCart = $request->getSession();
               $sessionCart->set('TotalToPay', $total);

               $order = $this->getDoctrine()
               ->getRepository(Sale::class)
               ->findOneBy(['orderid'=> $currentbuyid]);

               date_default_timezone_set('America/Santiago');
               $date = new \DateTime('now');

               if(!$order){
                 $order = new Sale();
                 $order->setOrderid($currentbuyid);
                 $order->setResume($orderresume);
                 $order->setTotal($total);
                 $order->setStatus("Orden Generada");
                 $order->setClientname($name);
                 $order->setClientmail($mail);
                 $order->setClientphone($phone);
                 $order->setDate($date);
               }else{
                 $order->setResume($orderresume);
                 $order->setTotal($total);
                 $order->setClientname($name);
                 $order->setClientmail($mail);
                 $order->setClientphone($phone);
                 $order->setDate($date);
               }


                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->persist($order);
                                $entityManager->flush();



               return new JsonResponse(array(
                          'total' => $total

                        ));

        }

      }

    /**
     * @Route("/tienda/pagar", name="store-checkout")
     */
    public function checkout(){

      return $this->render('frontend/store/checkout.html.twig', [

      ]);
    }

    /**
     * @Route("/tienda/payment/confirmacion/{id}", name="store-confirmation")
     */
     public function confirmPayment(Request $request, $id,  \Swift_Mailer $mailer){

       $params = array(
         "commerceId" => $id,
       );
       //Define el metodo a usar
       $serviceName = "payment/getStatusByCommerceId";
       try {
         // Ejecuta el servicio
         $response = $this->send($serviceName, $params,"GET");

         $sessionCart = $request->getSession();
         $sessionCart->set('ProductIDs', array());
         $sessionCart->set('buyid', "");

         $order = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findOneBy(['orderid'=> $id]);

         $status = $response['status'];

         if($order){
           switch($status){
             case 1:
                $order->setStatus('Pendiente de Pago');
             break;
             case 2:
                $order->setStatus('Pagado');
             break;
             case 3:
                $order->setStatus('Rechazado');
             break;
             case 4:
                $order->setStatus('Anulado');
             break;
             default:
              $order->setStatus('Orden Generada');
           }
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($order);
           $entityManager->flush();
         }

         $orderresume = json_decode($order->getResume());

         $arrayorder = json_decode($orderresume, true);

         $sendTo = $order->getClientmail();

         if($order == 2){

           $message = (new \Swift_Message('Hello Email'))
           ->setSubject('Área de Ventas - Arte Impreso')
           ->setFrom('joscri2698@gmail.com','Arte Impreso')
           ->setTo($sendTo)
           ->setBody(
             $this->renderView(
               'emails/clientorder.html.twig',
                array(
                  'resume' => $arrayorder,
                  'order' => $order,
                  'message' => $messageto
              )
             ),
             'text/html'
           );

           $mailer->send($message);

         }

           return $this->render('frontend/store/result/successful.html.twig', [
               'status' => $status,
               'title' => 'Tu Pedido',
               'resume' => $arrayorder,
               'order' => $order
           ]);

         //header("location:$redirect");

       } catch (Exception $e) {
         echo $e->getCode() . " - " . $e->getMessage();
         throw new Exception($e->getMessage());
         //return $this->redirect("https://www.arteimpreso.cl");
       }


    }

    /**
     * @Route("/orden/{id}", name="store-order")
     */
     public function obtainOrder($id){

       $order = $this->getDoctrine()
       ->getRepository(Sale::class)
       ->findOneBy(['orderid'=> $id]);

       $orderresume = json_decode($order->getResume());

       $arrayorder = json_decode($orderresume, true);

       return $this->render('frontend/store/result/order.html.twig', [
           'order' =>  $order,
           'resume' => $arrayorder,
           'title' => 'Estado de Orden'
       ]);

     }



    /**
     * @Route("/tienda/pagar/flow/{id}", name="store-checkout-flow")
     */
    public function payFlow($id){

      $order = $this->getDoctrine()
      ->getRepository(Sale::class)
      ->findOneBy(['orderid'=> $id]);

      if($order){
              $APIKEY = "6E8DE1F0-CAAB-43EA-86BA-4799L42C2069"; // Registre aquí su apiKey
              $SECRETKEY = "20bad08dda5c13b497091fcb0bdc30d936c19c53"; // Registre aquí su secretKey
              $APIURL = "https://flow.tuxpan.com/api"; // Producción EndPoint o Sandbox EndPoint
              $BASEURL = "https://www.arteimpreso.cl/"; //Registre aquí la URL base en su página donde instalará el cliente

              $total = $order->getTotal();
              $details = "owo";
              $id = $order->getOrderid();

        //$totalToPay = $total + ((6/100) * ($total));

           //Para datos opcionales campo "optional" prepara un arreglo JSON
      $optional = $order->getResume();
      //$optional = json_encode($optional);
      //Prepara el arreglo de datos
      $params = array(
        "commerceOrder" => $id,
        "subject" => "Pago ArteImpreso.cl",
        "currency" => "CLP",
        "amount" => $total,
        "email" => "joscri2698@gmail.com",
        "paymentMethod" => 9,
        "urlConfirmation" => $BASEURL ,
        "urlReturn" => $BASEURL . 'tienda/payment/confirmacion/' . $id,
        "optional" => $optional
      );
      //Define el metodo a usar
      $serviceName = "payment/create";
      try {
        // Ejecuta el servicio
        $response = $this->send($serviceName, $params,"POST");
        //Prepara url para redireccionar el browser del pagador
        $redirect = $response["url"] . "?token=" . $response["token"];
        //header("location:$redirect");
        return $this->redirect($redirect);
      } catch (Exception $e) {
        echo $e->getCode() . " - " . $e->getMessage();
        throw new Exception($e->getMessage());
        //return $this->redirect("https://www.arteimpreso.cl");
      }
      }


    }

    /**
    	 * Funcion que invoca un servicio del Api de Flow
    	 * @param string $service Nombre del servicio a ser invocado
    	 * @param array $params datos a ser enviados
    	 * @param string $method metodo http a utilizar
    	 * @return string en formato JSON
    	 */
    	public function send( $service, $params, $method = "GET") {
    		$method = strtoupper($method);
    		$url = 'https://flow.tuxpan.com/api' . "/" . $service;
    		$params = array("apiKey" => "6E8DE1F0-CAAB-43EA-86BA-4799L42C2069") + $params;
    		$data = $this->getPack($params, $method);
    		$sign = $this->sign($params);
    		if($method == "GET") {
    			$response = $this->httpGet($url, $data, $sign);
    		} else {
    			$response = $this->httpPost($url, $data, $sign);
    		}

    		if(isset($response["info"])) {
    			$code = $response["info"]["http_code"];
    			$body = json_decode($response["output"], true);
    			if($code == "200") {
    				return $body;
    			} elseif(in_array($code, array("400", "401"))) {
    				throw new Exception($body["message"], $body["code"]);
    			} else {
    				throw new Exception("Unexpected error occurred. HTTP_CODE: " .$code , $code);
    			}
    		} else {
    			throw new Exception("Unexpected error occurred.");
    		}
    	}

      /**
	 * Funcion que empaqueta los datos de parametros para ser enviados
	 * @param array $params datos a ser empaquetados
	 * @param string $method metodo http a utilizar
	 */
	private function getPack($params, $method) {
		$keys = array_keys($params);
		sort($keys);
		$data = "";
		foreach ($keys as $key) {
			if($method == "GET") {
				$data .= "&" . rawurlencode($key) . "=" . rawurlencode($params[$key]);
			} else {
				$data .= "&" . $key . "=" . $params[$key];
			}
		}
		return substr($data, 1);
	}


	/**
	 * Funcion que firma los parametros
	 * @param string $params Parametros a firmar
	 * @return string de firma
	 */
	private function sign($params) {
		$keys = array_keys($params);
		sort($keys);
		$toSign = "";
		foreach ($keys as $key) {
			$toSign .= "&" . $key . "=" . $params[$key];
		}
		$toSign = substr($toSign, 1);
		if(!function_exists("hash_hmac")) {
			throw new Exception("function hash_hmac not exist", 1);
		}

    //Development
		return hash_hmac('sha256', $toSign , "20bad08dda5c13b497091fcb0bdc30d936c19c53");
    
	}

  /**
	 * Funcion que hace el llamado via http GET
	 * @param string $url url a invocar
	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON
	 */
	private function httpGet($url, $data, $sign) {
		$url = $url . "?" . $data . "&s=" . $sign;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}

  /**
	 * Funcion que hace el llamado via http POST
	 * @param string $url url a invocar
	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON
	 */
	private function httpPost($url, $data, $sign ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data . "&s=" . $sign);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}

}
