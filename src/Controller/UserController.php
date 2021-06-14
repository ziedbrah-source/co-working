<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}/', name: 'user_show')]
    public function index(User $user): Response
    {
        if ($this->getUser() == $user){return $this->redirectToRoute('account_index_default',['page' => 1]);}
        else {
            $reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findBy(['User' => $user->getId()]);
            return $this->render('/user/index.html.twig', ['user' => $user,
                'reservations' => $reservations,
                'nombrereservations' => count($reservations),
                'pagename' => ($user->getFullName() . "'s profile")]);
        }
    }
}
