<?php


namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReservationType;
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
     * @Route ("/reservationSuccess/{reservation}",name="reservation_success")
     */
    public function ReservationReussit(Reservation  $reservation = null){
        return $this->render('Services/reservation_success.html.twig',[
            "reservation"=> $reservation
        ]);
    }
    /**
     * @Route ("/ReservationForm/{salle}",name="salle.reservation")
     *
     */
    public function ReservationSalle(EntityManagerInterface $manager,Salle $salle= null,Request $request){
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $user = $this->getUser();
            $reservation->setUser($user)
                ->setSalle($salle);
            if (!$reservation->isBookableDays()) {
                $this->addFlash(
                    'error',
                    "les dates que vous avez choisit sont completes"
                );
            } else {

                $manager->persist($reservation);
                $manager->flush();
                return ($this->render('Services/reservation_success.html.twig', ['reservation' => $reservation]));
            }

        }
        return $this->render('Services/reservationSalle.html.twig',[

            'form'=> $form->createView(),
            'salle'=>$salle
        ]);


}
}