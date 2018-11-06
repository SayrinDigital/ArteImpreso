<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Service;

class HomeController extends AbstractController
{
    /**
     * @Route("/inicio", name="home")
     */
    public function index()
    {

      $services = $this->getDoctrine()
        ->getRepository(Service::class)
        ->findAll();

      $products = $this->getDoctrine()
        ->getRepository(Product::class)
        ->findLatestProducts(10);

        return $this->render('frontend/home/index.html.twig', [
          'products' => $products,
          'services' => $services
        ]);
    }
}
