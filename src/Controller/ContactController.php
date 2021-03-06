<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contacto", name="contact")
     */
    public function index()
    {
        return $this->render('frontend/contact/index.html.twig', [
            'title' => 'Contacto'
        ]);
    }
}
