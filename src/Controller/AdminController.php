<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\User;
use App\Form\AdminReservationsOperationsType;
use App\Form\ReservationType;
use App\Service\Pagination;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * @Route("/admin/Reservations/{page<\d+>?1}", name="admin_reservations")
     * @return Response
     */
    function getReservations($page,Pagination $pagination){
        //Getting all reservations.
        $pagination->setEntityClass(Reservation::class)->setPage($page);
        $reservations = $pagination->getData([]);

        return $this->render('admin/showAllReservations.html.twig',
            ['reservations' => $reservations,
                'pagination'=>$pagination]);
    }
    /**
     *
     * @Route("/admin/Reservations/edit/{id<\d+>?1}", name="admin_reservations_edit")
     *
     */
    public function ReservationEdit($id){//, Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $reservation = $entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException(
                "Pas de réservation d'ID = ".$id
            );
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        //$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/reservationModifier.html.twig', [
            'registrationForm' => $form->createView(),
            'reservation'=> $reservation,
        ]);
    }
    /**
     *
     * @Route("/admin/Reservations/delete", name="admin_reservations_delete")
     *
     */
    public function ReservationDelete(){
        return $this->redirectToRoute('admin');
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
