<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Service;
use App\Entity\Gallery;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services/{serviceId}", name="services")
     */
    public function index($serviceId)
    {

        $service = $this->getDoctrine()->getRepository(Service::class)->findOneById($serviceId);

        return $this->render('frontend/services/index.html.twig', [
            'service' => $service
        ]);
    }

    public function fillAllServices(){
      $services = $this->getDoctrine()->getRepository(Service::class)->findAll();

      return $this->render('frontend/components/servicesnav.html.twig', [
          'services' => $services,
      ]);
    }

}
