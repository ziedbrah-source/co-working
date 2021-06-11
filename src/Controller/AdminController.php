<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     *
     * @Route("/admin/ReservationsList", name="admin_users_show")
     *
     */
    function getReservations(){
        //Getting all reservations.
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();
        return $this->render('admin/showAllReservations.html.twig',
            ['reservations' => $reservations]);
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
