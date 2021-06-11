<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     *
     * @Route("/admin/users", name="admin_users_show")
     *
     */
    function getUsers(){
        //Getting only verified users.
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['isVerified' => 1]);
        //Getting all salles.
        $salles = $this->getDoctrine()
            ->getRepository(Salle::class)
            ->findAll();
        return $this->render('admin/showUserGroup.html.twig',
            ['users' => $users,
            'salles' => $salles,
            'numbersalles'=> count($salles)]);
    }
    /**
     *
     * @Route("/admin/user{id}", name="admin_user_show")
     *
     */
    function getUserById(int $id){
        //Getting the user with id = $id.
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        //Getting all salles.
        $salles = $this->getDoctrine()
            ->getRepository(Salle::class)
            ->findAll();
        //If user with id = $id does not exist :
        if (!$user) {
            return $this->render('admin/NoUser.html.twig',['id' => $id]);
        }
        //If user does exist:
        return $this->render('admin/showUser.html.twig',
            ['id' => $id,
            'fullname' => $user->getFullName(),
            'email' => $user->getEmail(),
            'salles' => $salles,
            'numbersalles'=> count($salles),
            'is_verified' => $user->isVerified()]);
    }



    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
