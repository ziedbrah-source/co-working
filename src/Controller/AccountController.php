<?php

namespace App\Controller;

use App\Form\AccountType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

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
}
