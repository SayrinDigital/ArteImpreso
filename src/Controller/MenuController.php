<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @Route("/", name="menu")
     */
    public function index()
    {
        return $this->render('frontend/menu/index.html.twig', [

        ]);
    }
}
