<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\User;
use App\Form\AdminReservationsOperationsType;
use App\Form\ReservationType;
use App\Form\SalleCreationType;
use App\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/admin/Reservations/delete{id<\d+>?1}", name="admin_reservations_delete")
     *
     */
    public function ReservationDelete($id){
        $reservation = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($reservation);
        $manager->flush();
        return $this->redirectToRoute('admin_reservations');
    }
    /**
     *
     * @Route("/admin/Salles/{page<\d+>?1}", name="admin_salles")
     *
     */
    function getSalles(Pagination $pagination, $page){
        $pagination->setEntityClass(Salle::class)->setPage($page);
        $salles = $pagination->getData([]);

        return $this->render('admin/showAllSalles.html.twig',
            ['salles' => $salles,
                'pagination'=>$pagination]);
    }
    /**
     *
     * @Route("/admin/Salles/create", name="admin_salles_create")
     *
     */
    public function createSalle(Request $request){
        $salle = new Salle();
        $form = $this->createForm(SalleCreationType::class, $salle);
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($salle);
            $entityManager->flush();
            $this->addFlash('success', 'Salle Creé!');
            return $this->redirectToRoute("admin_salles");
        }

        return $this->render('admin/newSalle.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     *
     * @Route("/admin/Salles/equips{id<\d+>?1}", name="admin_salles_equipements")
     *
     */
    function setEquipsSalle( $id){

    }
    /**
     *
     * @Route("/admin/Salles/photo{id<\d+>?1}", name="admin_salles_photo")
     *
     */
    function setPhotoSalle( $id){}
    /**
     *
     * @Route("/admin/Salles/prix{id<\d+>?1}", name="admin_salles_prix")
     *
     */
    function setPrixSalle( $id){
        $salle= $this->getDoctrine()->getRepository(Salle::class)->find($id);

    }
    /**
     *
     * @Route("/admin/Salles/delete{id<\d+>?1}", name="admin_salles_delete")
     *
     */
    function delSalle( $id){
        $salle = $this->getDoctrine()
            ->getRepository(Salle::class)
            ->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($salle);
        $manager->flush();
        return $this->redirectToRoute('admin_salles');
    }
    /**
     *
     * @Route("/admin/Users/{page<\d+>?1}", name="admin_users")
     *
     */
    function getUsers(Pagination $pagination, $page){
        $pagination->setEntityClass(User::class)->setPage($page);
        $users = $pagination->getData([]);

        return $this->render('admin/showAllUsers.html.twig',
            ['users' => $users,
                'pagination'=>$pagination]);
    }
    /**
     *
     * @Route("/admin/Users/ban{id<\d+>?1}", name="admin_user_ban")
     *
     */
    function banUser($id){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_users');
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',

        ]);
    }
}
