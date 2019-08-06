<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller {
    /**
     * 
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello", name= "hello_base")
     * @Route("/hello/{prenom}", name= "hello_prenom")
     * Montre une page qui dit bonjour
     * 
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0){
        return $this->render(
            'hello.html.twig',
            [
                'prenom'    => $prenom,
                'age'       => $age
            ]
        );
    }

    /**
     * Lead to home page
     * 
     * @Route("/", name="homepage")
     *
     * @return void
     */
    public function home(){
    $prenoms = ["Amar" => '31', "Ali" => '10', "Amir" => '12', "Ahmed" => '14'];
        return $this->render(
            'home.html.twig', 
            [
                'title'     => 'Bonjour à tous !',
                'age'       => '18',
                'tableau'   => $prenoms
            ]
        );
    }
}
