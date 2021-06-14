<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * Permet de modifier le mot de passe
     * @Route("/news/id={id}",name="news_article")
     * @return Response
     */
    public function getNews($id){
        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findBy(['id' => $id]);
        return $this->render('news/indew.html.twig',[
            'news' => $news,
        ]);
    }

    #[Route('/news', name: 'news')]
    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
        ]);
    }
}
