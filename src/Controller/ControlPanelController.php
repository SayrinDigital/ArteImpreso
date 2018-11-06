<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Service;

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
            'headertitle' => 'Servicios - Sayrin Panel',
            'services' => $services
        ]);
    }

    /**
     * @Route("panel/tienda", name="controlpanel-store")
     */
    public function storePanel(){
      return $this->render('controlpanel/store/main.html.twig', [
        'headertitle' => 'Tienda - Sayrin Panel',
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
          'headertitle' => 'Categorías - Sayrin Panel',
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
          'headertitle' => $categoryName . ' - Sayrin Panel',
            'category' => $category
        ]);
    }

    /**
     * @Route("panel/tienda/ventasnotificadas", name="controlpanel-store-notifiedsales")
     */
    public function notifiedSalesPanel(){
      return $this->render('controlpanel/store/notifiedsales.html.twig', [
        'headertitle' => 'Ventas Notificadas - Tienda - Sayrin Panel',
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
