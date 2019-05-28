<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Sale;

class ControlPanelController extends AbstractController
{

  /**
   * @Route("panel", name="controlpanel")
   */
   public function showPanel(){

    return $this->redirectToRoute('controlpanel-services');

   }

    /**
     * @Route("panel/servicios", name="controlpanel-services")
     */
    public function servicesPanel()
    {

      $services = $this->getDoctrine()
      ->getRepository(Service::class)
      ->findAll();

        return $this->render('controlpanel/services/index.html.twig', [
            'headertitle' => 'Servicios',
            'services' => $services
        ]);
    }

    /**
     * @Route("panel/tienda", name="controlpanel-store")
     */
    public function storePanel(){
      return $this->render('controlpanel/store/main.html.twig', [
        'headertitle' => 'Tienda',
      ]);
    }

    /**
     * @Route("panel/categorias", name="controlpanel-categories")
     */
    public function categoriesPanel()
    {

      $categories = $this->getDoctrine()
             ->getRepository(Category::class)
             ->findAll();

        return $this->render('controlpanel/store/categories.html.twig', [
          'headertitle' => 'Categorías',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("panel/categorias/{categoryId}/productos", name="controlpanel-categorybyid")
     */
    public function productsByCategoryPanel($categoryId)
    {

      $category = $this->getDoctrine()
           ->getRepository(Category::class)
           ->find($categoryId);

           $categoryName = ucfirst($category->getName());

        return $this->render('controlpanel/store/index.html.twig', [
          'headertitle' => $categoryName,
            'category' => $category
        ]);
    }

    /**
     * @Route("panel/tienda/ordenesdeventa", name="controlpanel-store-saleorders")
     */
    public function saleOrdersPanel(){

      $orders = $this->getDoctrine()
      ->getRepository(Sale::class)
      ->findAll();

      return $this->render('controlpanel/store/saleorders.html.twig', [
        'headertitle' => 'Órdenes de Venta',
        'orders' => $orders
      ]);
    }

    /**
     * @Route("panel/tienda/ordenesdeventa/{id}", name="controlpanel-store-saleordersbycategory")
     */
     public function saleOrdersPanelbyCategory($id){

       switch($id){
         case 1:
         $orders = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findBy(['status' => 'Pendiente de Pago']);
         break;
         case 2:
         $orders = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findBy(['status' => 'Pagado']);
         break;
         case 3:
         $orders = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findBy(['status' => 'Rechazado']);
         break;
         case 4:
         $orders = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findBy(['status' => 'Anulado']);
         break;
         default:
         $orders = $this->getDoctrine()
         ->getRepository(Sale::class)
         ->findBy(['status' => 'Orden Generada']);
       }

       return $this->render('controlpanel/store/saleorders.html.twig', [
         'headertitle' => 'Órdenes de Venta',
         'orders' => $orders
       ]);

     }

    public function fillCategoriesPanel(){

      $categories = $this->getDoctrine()
             ->getRepository(Category::class)
             ->findBy([],['id'=>'DESC']);

             return $this->render('controlpanel/components/categoriespanel.html.twig', [
                 'categories' => $categories
             ]);
    }

    public function fillCategories(){
      $categories = $this->getDoctrine()
             ->getRepository(Category::class)
             ->findBy([],['id'=>'DESC']);

             return $this->render('controlpanel/components/categoriespanel-detailed.html.twig', [
                 'categories' => $categories
             ]);
    }

    public function fillProductCategoriesProduct(){
      $categories = $this->getDoctrine()
             ->getRepository(Category::class)
             ->findAll();

             return $this->render('controlpanel/components/productselectcategories.html.twig', [
                 'categories' => $categories
             ]);

    }

}
