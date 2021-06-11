<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'user_show')]
    public function index(User $user): Response
    {
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBy(['User' => $user->getId()]);
        return $this->render('/user/index.html.twig',['user'=>$user,
            'reservations' => $reservations,
            'nombrereservations'=>count($reservations)]);
    }
}
