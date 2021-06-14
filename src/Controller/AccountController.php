<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\ReservationType;
use App\Service\Pagination;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    /**
     * Profile d'un utlisateur
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
            return $this->redirectToRoute("account_index",['page' => 1]);
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
                $response = $this->forward('App\Controller\MailerControllerPhpController::sendEmail', [
                    'email' => $user->getEmail(),
                ]);

                // ... further modify the response or return it directly

                return $response;

            }
        }
        return $this->render('account/password.html.twig', [
            'form'=> $form->createView()
        ]);
    }
    /**
     * Permet d'annuler une réservation
     * @Route("/account/cancel{id<\d+>?1}",name="account_cancel_reservation")
     * @return Response
     */
    public function cancelReservation($id){
        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($reservation);
        $manager->flush();
        return $this->redirectToRoute('account_index');
    }
    /**
     *
     * @Route("/account/Reservations/edit/{id<\d+>?1}", name="account_reservations_edit")
     *
     */
    public function ReservationEdit($id, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);
        if (!$reservation) {
            throw $this->createNotFoundException(
                "Pas de réservation d'ID = ".$id
            );
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/reservationModifier.html.twig', [
            'form' => $form->createView(),
            'reservation'=> $reservation,
        ]);
    }
    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * @Route("/account/{page<\d+>?1}", name="account_index")
     * @return Response
     */

    public function myAccount($page = 1,Pagination $pagination){
        //Getting all reservations.
        $user = $this->getUser();
        $pagination->setEntityClass(Reservation::class)->setPage($page);

        $reservations = $pagination->getData(['User' => $user->getId()]);
        return $this->render('user/index.html.twig',
            ['reservations' => $reservations,
                'pagination'=>$pagination,
                'user' =>$user,
                'pagename'=>"My Account",
                'nombrereservations' => count($reservations)]);
        }
    }
