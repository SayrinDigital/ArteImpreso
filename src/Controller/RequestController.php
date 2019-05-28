<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Config\Definition\Exception\Exception;


class RequestController extends AbstractController
{
    /**
     * @Route("/request/createnewservice", name="request-addnewservice")
     */
    public function addnewService(Request $request)
    {

    if ($servicecoverfile = $request->files->get('servicecover')) {
        $servicename = $request->get('servicename');
        $servicedescription = $request->get('servicedescription');
        $servicecontent = $request->get('servicecontent');

        $servicecoverfileName = md5(uniqid()).'.'.$servicecoverfile->guessExtension();
        $servicecoverfile->move(
               $this->getParameter('components_directory'),
               $servicecoverfileName
           );

           $service = new Service();
           $service->setName($servicename);
           $service->setDescription($servicedescription);
           $service->setCover($servicecoverfileName);
           $service->setFirstConcept("");
           $service->setSecondConcept("");
           $service->setOrientation("left");
           $service->setContent($servicecontent);

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($service);
           $entityManager->flush();

           return new JsonResponse(array(
                      'name' => $servicename,
                      'desc' => $servicedescription,
                      'cover' => $servicecoverfileName
                    ));
    }

    }

    /**
     * @Route("/request/modifyservice", name="request-modifyservice")
     */
    public function modifyService(Request $request)
    {

    if ($serviceid = $request->get('serviceid')) {
        $servicename = $request->get('servicename');
        $servicecoverfile = $request->files->get('servicecover');
        $servicedescription = $request->get('servicedescription');
        $servicecontent = $request->get('servicecontent');

           $entityManager = $this->getDoctrine()->getManager();
        $service = $entityManager->getRepository(Service::class)->findOneById($serviceid);

       if($servicecoverfile!=null){
         $servicecoverfileName = md5(uniqid()).'.'.$servicecoverfile->guessExtension();
         $servicecoverfile->move(
                $this->getParameter('components_directory'),
                $servicecoverfileName
            );
         $service->setCover($servicecoverfileName);
       }



           $service->setName($servicename);
           $service->setDescription($servicedescription);
           $service->setFirstConcept("");
           $service->setSecondConcept("");
           $service->setOrientation("left");
           $service->setContent($servicecontent);

           $entityManager->persist($service);
           $entityManager->flush();

           return new JsonResponse(array(
                      'name' => $servicename,
                    ));

    }

    }


    /**
     * @Route("/request/deleteservice", name="request-deleteservice")
     */
     public function removeService(Request $request){

       if($serviceId = $request->request->get('serviceId')){

         $entityManager = $this->getDoctrine()->getManager();
         $service = $entityManager->getRepository(Service::class)->findOneById($serviceId);
         $entityManager->remove($service);
         $entityManager->flush();

         return new JsonResponse(array(
                    'id' => $serviceId,
                  ));

       }


     }

     /**
      * @Route("/request/createnewcategory", name="request-addcategory")
      */
      public function addnewCategory(Request $request){

       if($categoryName = $request->request->get('categoryName')){
           $category = new Category();
           $category->setName($categoryName);
           $category->setCover("");

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($category);
           $entityManager->flush();

           $answer['html'] = $this->render('controlpanel/components/category.html.twig', [ 'category' => $category ])->getContent();
           $response = new Response();
           $response->setContent(json_encode($answer));

           return $response;

       }

       return $this->render('request/index.html.twig', [

       ]);

      }

      /**
       * @Route("/request/deletecategory", name="request-deletecategory")
       */
       public function removeCategory(Request $request){

         if($categoryId = $request->request->get('categoryId')){

           $entityManager = $this->getDoctrine()->getManager();
           $category = $entityManager->getRepository(Category::class)->findOneById($categoryId);
           $entityManager->remove($category);
           $entityManager->flush();

           return new JsonResponse(array(
                      'id' => $categoryId,
                    ));
         }

       }

       /**
        * @Route("/request/changecategoryname", name="request-changecategoryname")
        */
        public function changeCategoryName(Request $request){

          if($categoryId = $request->request->get('categoryId')){

            $categoryName = $request->request->get('categoryName');

            $entityManager = $this->getDoctrine()->getManager();
            $category = $entityManager->getRepository(Category::class)->findOneById($categoryId);
            $category->setName($categoryName);
            $entityManager->flush();

            return new JsonResponse(array(
                       'id' => $categoryId,
                       'name' => $categoryName
                     ));
          }
        }

        /**
         * @Route("/request/sendcontactmail", name="request-sendcontactmail")
         */
         public function sendContactMail(Request $request, \Swift_Mailer $mailer){

           if($mailto = $request->request->get('mail')){
                     $phoneto = $request->request->get('phone');
                     $nameto = $request->request->get('name');
                     $messageto = $request->request->get('message');

                     $message = (new \Swift_Message('Hello Email'))
                     ->setSubject('Contacto - Arte Impreso')
                     ->setFrom('joscri2698@gmail.com','Arte Impreso')
                     ->setTo('josepuma@sayrin.cl')
                     ->setBody(
                       $this->renderView(
                         'emails/contact.html.twig',
                          array(
                            'name' => $nameto,
                            'phone' => $phoneto,
                            'message' => $messageto,
                            'mail' => $mailto
                        )
                       ),
                       'text/html'
                     );

                     $mailer->send($message);
                     return new JsonResponse(array(
                                'mail' => $mailto
                              ));

           }

           return $this->render('request/index.html.twig', [

           ]);
         }

         /**
          * @Route("/request/createnewproduct", name="request-addnewproduct")
          */
         public function addnewProduct(Request $request)
         {

         if ($productcoverfile = $request->files->get('productcover')) {
             $productName = $request->get('productname');
             $productCategoryOption = $request->get('productcategoryoption');
             $productPrice = $request->get('productprice');
             $productDescription = $request->get('productdescription');
             //$productcoversmallfile = $request->files->get('productcoversmall');



             $productcoverfileName = md5(uniqid()).'.'.$productcoverfile->guessExtension();
             $productcoverfile->move(
                    $this->getParameter('productcovers_directory'),
                    $productcoverfileName
                );

                /*$productcoverfileSmallName = md5(uniqid()).'.'.$productcoversmallfile->guessExtension();
                $productcoversmallfile->move(
                       $this->getParameter('productcovers_directory'),
                       $productcoverfileSmallName
                   );*/

                $entityManager = $this->getDoctrine()->getManager();
                $category = $entityManager->getRepository(Category::class)->findOneById($productCategoryOption);

                $product = new product();
                $product->setName($productName);
                $product->setPrice($productPrice);
                $product->setCover($productcoverfileName);
                $product->setDescription($productDescription);
                //$product->setSmallCover($productcoverfileSmallName);
                $product->setSmallCover($productcoverfileName);
                $product->setCategory($category);


                $entityManager->persist($product);
                $entityManager->flush();

                $answer['producthtml'] = $this->render('controlpanel/components/product.html.twig', [ 'product' => $product ])->getContent();
                $productresponse = new Response();
                $productresponse->setContent(json_encode($answer));

                return $productresponse;

         }

         return $this->render('request/index.html.twig', [

         ]);

         }

         /**
          * @Route("/request/deleteproduct", name="request-deleteproduct")
          */
          public function removeProduct(Request $request){

            if($productId = $request->request->get('productid')){

              $entityManager = $this->getDoctrine()->getManager();
              $product = $entityManager->getRepository(Product::class)->findOneById($productId);
              $entityManager->remove($product);
              $entityManager->flush();

              return new JsonResponse(array(
                         'id' => $productId,
                       ));

            }


          }

          /**
           * @Route("/request/modifyproduct", name="request-modifyproduct")
           */
          public function modifyProduct(Request $request){

          if($productid = $request->request->get('productid')){
            $productName = $request->get('productname');
            $productCategoryOption = $request->get('productcategory');
            $productPrice = $request->get('productprice');
            $productDescription = $request->get('productdescription');

            $entityManager = $this->getDoctrine()->getManager();
            $product = $entityManager->getRepository(Product::class)->findOneById($productid);
            $category = $entityManager->getRepository(Category::class)->findOneById($productCategoryOption);

            $product->setName($productName);
            $product->setPrice($productPrice);
            $product->setDescription($productDescription);
            $product->setCategory($category);

            $entityManager->flush();

            return new JsonResponse(array(
                       'id' => $productid,
                     ));

          }

          }

          /**
           * @Route("/request/modifycategorycover", name="request-modifycategorycover")
           */
           public function modifyCategoryCover(Request $request){

             $fileSystem = new Filesystem();

                  if($productcoverfile = $request->files->get('cover')){

                    $id = $request->get('id');

                    $productcoverfileName = md5(uniqid()).'.'.$productcoverfile->guessExtension();
                    $productcoverfile->move(
                           $this->getParameter('components_directory'),
                           $productcoverfileName
                       );

                       $entityManager = $this->getDoctrine()->getManager();
                       $category = $entityManager->getRepository(Category::class)->findOneById($id);

                       $oldName = $category->getCover();
                       $fullPath = $this->getParameter('components_directory').$oldName ;
                       if($oldName!=""){
                         $fileSystem->remove($fullPath);
                       }

                       $category->setCover($productcoverfileName);
                       $entityManager->flush();


                       return new JsonResponse(array(
                                  'path' => $productcoverfileName,
                                ));


                  }

           }

}
