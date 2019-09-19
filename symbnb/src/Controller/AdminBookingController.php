<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking", name="admin_booking")
     */
    public function index()
    {
        return $this->render('admin_booking/index.html.twig', [
            'controller_name' => 'AdminBookingController',
        ]);
    }
}
