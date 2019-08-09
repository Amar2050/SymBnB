<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ad_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
    
        /**
         * Create a Ad
         * 
         * @Route ("/ads/new", name="ads_create")
         * 
         * @return Response
         */
    
        public function create(){
            return $this->render('ad/new.html.twig');
        }

    /**
     * Display one ad only
     * 
     * @Route("/ads/{slug}", name="ad_show")
     * 
     * @return Response
     */
    public function show(Ad $ad){
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);

    }
} 
