<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    /**
     * @Route("/modal/venta-realizada", name="messages-successfulsale")
     */
    public function succesfullSaleModal()
    {
        return $this->render('frontend/messages/index.html.twig', [
          'title' => 'Gracias Por tu compra',
          "message" => 'Los detalles de tu compra fueron enviados al correo que nos entregaste. Nos contactaremos contigo a la brevedad.'
        ]);
    }

    /**
     * @Route("/modal/mensaje-enviado", name="messages-successfulmessage")
     */
    public function succesfullMessageModal()
    {
        return $this->render('frontend/messages/index.html.twig', [
          'title' => 'Recibimos tu mesaje',
          "message" => 'Nos contactaremos contigo a la brevedad.'
        ]);
    }


}
