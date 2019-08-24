<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Login form
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig' , [
            'hasError' => $error !== null,
            'username'     => $username
        ]);
    }

    /**
     * Logout
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout() {
        //...waelou !
    }

    /**
     * Display register form
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form= $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success' ,
                "Bienvenue <strong>{$user->getFirstname()}</strong> !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form'  =>  $form->createView()
        ]);
    }
    /**
     * Updating user profil
     * 
     * @Route("/account/profile", name="account_profile")
     * 
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success' ,
                "Votre profil as bien été mis à jour !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form'  => $form->createView()
        ]);
    }
    /**
     * Updating password
     *
     * @Route("/account/password-update", name="account_password")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            //Checking oldPassword validity
            if( !password_verify($passwordUpdate->getOldpassword(), $user->getHash()) ) {
                // Handle error
                $form->get('oldPassword')->addError(new FormError("Vous n'avez 
                pas entré le bon mot de passe !"));
                // $this->addFlash(
                // 'danger' ,
                // "Vous n'avez pas entré le bon mot de passe !"
                // );
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success' ,
                    "Votre mot de passe as bien été mis à jour !"
                );

                return $this->redirectToRoute('homepage');
            }
            
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * Display connected user profile
     *
     * @Route("/account",name="account_index")
     * 
     * @return Response
     */
    public function myAccount(){

        return $this->render('user/index.html.twig', [
            'user'  => $this->getUser()
        ]);

    }

}
