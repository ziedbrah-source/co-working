<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\User;
use App\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     *
     * @Route("/admin/Reservations", name="admin_reservations_default")
     *
     */
    function RedirectToDefaultIndice(){
        return $this->redirectToRoute("admin_reservations_show", ['page' => 1]);
    }
    /**
     *
     * @Route("/admin/Reservations/p={page}", name="admin_reservations_show")
     *
     */
    function getReservations($page,Pagination $pagination){
        //Getting all reservations.
        $pagination->setEntityClass(Reservation::class)->setPage($page);
        $reservations = $pagination->getData();
        return $this->render('admin/showAllReservations.html.twig',
            ['reservations' => $reservations,
                'pagination'=>$pagination]);
    }
    /**
     *
     * @Route("/admin/SalleList", name="admin_salles_show")
     *
     */
    function getSalles(){
        //Getting all reservations.
        $salles = $this->getDoctrine()
            ->getRepository(Salle::class)
            ->findAll();
        return $this->render('admin/showAllSalles.html.twig',
            ['salles' => $salles]);
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',

        ]);
    }
}
