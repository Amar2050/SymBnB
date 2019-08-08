<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController {

    /**
     * Lead to home page
     * 
     * @Route("/", name="homepage")
     *
     * @return void
     */
    public function home(){
        return $this->render(
            'home.html.twig'
        );
    }
}
