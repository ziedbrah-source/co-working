<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    /**
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class ,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash("success","Les données du Profil ont été enregistrer");
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de modifier le mot de passe
     * @Route("/account/password-update",name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager){
        $user= $this->getUser();
        $passwordUpdate = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword()) ){
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez n'est pas votre mot de passe actuel !"));

            }else{
                $newPassword=$passwordUpdate->getNewPassword();
                $password=$encoder->encodePassword($user,$newPassword);

                $user->setPassword($password);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash("success","Votre mot de passe a bien été modifié!");
                return $this->redirectToRoute("home");
            }
        }
        return $this->render('account/password.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * @Route("/account", name="account_index")
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }

    }