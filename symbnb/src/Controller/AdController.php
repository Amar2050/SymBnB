<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    
        public function create(Request $request, ObjectManager $manager){
            $ad = new Ad;

            $form = $this->createForm(AdType::class, $ad);

            $form->handleRequest($request);
            

            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($ad);
                $manager->flush();

                $this->addFlash(
                    'success' ,
                    "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
                );
                
                return $this->redirectToRoute('ad_show', [
                    'slug' => $ad->getSlug()
                ]);

            }

            return $this->render('ad/new.html.twig',  [
                'form' => $form->createView()

            ]);
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
