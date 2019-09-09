<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */

    public function create(Request $request, ObjectManager $manager){
        $ad = new Ad;

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        foreach ($ad->getImages() as $image) {
            $image->setAd($ad);
            $manager->persist($image);
        }
        
        $ad->setAuthor($this->getUser());

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
     * Updating an Ad
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous ne pouvez pas modifier cette annonce")
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        
        foreach ($ad->getImages() as $image) {
            $image->setAd($ad);
            $manager->persist($image);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success' ,
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée !"
            );
            return $this->redirectToRoute('ad_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig' , [
            'form' => $form->createView(),
            'ad'   => $ad 
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
            'ad'    => $ad
        ]);

    }

    /**
     * Deleting ads
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous ne pouvez pas supprimer cette annonce")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager){
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success' ,
            "L'annonce <strong>{$ad->getTitle()}</strong> as bien été supprimé"
        );

        return $this->redirectToRoute("ad_index");
    }
} 
