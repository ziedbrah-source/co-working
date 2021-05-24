<?php


namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    /**
     * @Route("/Services",name="Services")
     */
    public function first(){
        return $this->render('Services/index.html.twig',[
            'controller_name' => 'SecondController'
        ]);
    }
    /**
     * @Route("/Reservation",name="Reservation")
     */
    public function reservation(){
        $repository = $this->getDoctrine()->getRepository('App:Salle');
        $salles= $repository->findBy([],['prix'=>'asc']);

        return $this->render('Services/reservation.html.twig',[
            'controller_name' => 'ReservationController',
            'salles'=>$salles
        ]);
    }

    /**
     * @Route ("/detail/{salle}",name="salle.detail")
     */
    public function detailSalle(Salle $salle = null){

        return $this->render('Services/detail.html.twig',[
            'salle'=> $salle
        ]);
    }
    /**
     * @Route ("/ReservationForm/{salle}",name="salle.reservation")
     */
    public function ReservationSalle(EntityManagerInterface $manager,Salle $salle= null){
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$Reservation);
        return $this->render('Services/reservationSalle.html.twig',[

            'form'=> $form->createView(),
            'salle'=>$salle
        ]);
    }
}