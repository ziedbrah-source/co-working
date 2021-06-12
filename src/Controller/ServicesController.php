<?php


namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;

use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReservationType;
class ServicesController extends AbstractController
{
    /**
     * @Route("/Reservation/{page<\d+>?1}",name="Reservation",requirements={"page"="\d+"})
     */
    public function reservation($page, Pagination $pagination){
        $pagination->setEntityClass(Salle::class)
                    ->setPage($page) ;//par default 10 le limit
        $salles= $pagination->getData();





        return $this->render('Services/reservation.html.twig',[
            'salles'=>$pagination->getData(),
            'pagination' => $pagination,

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