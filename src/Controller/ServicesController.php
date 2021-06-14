<?php


namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Salle;

use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ReservationType;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
class ServicesController extends AbstractController
{
    private $knpSnappy;
    public function __construct(\Knp\Snappy\Pdf $knpSnappy) { $this->knpSnappy = $knpSnappy; }
    /**
     * @Route("/Reservation/{page<\d+>?1}",name="Reservation",requirements={"page"="\d+"})
     */
    public function reservation($page, Pagination $pagination){
        $pagination->setEntityClass(Salle::class)
                    ->setPage($page) ;//par default 10 le limit





        return $this->render('Services/reservation.html.twig',[
            'salles'=>$pagination->getData([]),
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
     * @Route ("/reservationSuccess/{reservation}",name="reservation_success")
     */
    public function ReservationReussit(Reservation  $reservation = null){
        return $this->render('Services/show.html.twig',[
            "reservation"=> $reservation,
        ]);
    }
    /**
     * @Route ("/reservation/{reservation}/generatePdf",name="reservation_pdf")
     */
    public function ReservationPdf(Reservation  $reservation = null){

            $html = $this->renderView('Services/pdf.html.twig', array(
                "reservation"=> $reservation,
            ));

        return new PdfResponse(
            $this->knpSnappy->getOutputFromHtml($html),
            'file.pdf'
        );

    }
    /**
     * @Route ("/reservation/{id}", name="reservation_show")
     * @param Reservation $reservation
     */
    public function show(Reservation $reservation){
        return $this->render('Services/show.html.twig',[
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
                return $this->redirectToRoute('reservation_success',[
                    "reservation"=> $reservation->getId(),
                    "withAlert"=> true,
                ]);
            }

        }
        return $this->render('Services/reservationSalle.html.twig',[

            'form'=> $form->createView(),
            'salle'=>$salle
        ]);


}
}