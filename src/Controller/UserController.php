<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;

use App\Repository\UserRepository;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/users", name="users")
     */
    public function users(UserRepository $userRepository): Response
    {
        $users=$userRepository->findAll();
        
        return $this->render('user/list.html.twig', ['users'=>$users]);
    }

    /**
     * @Route("/users/add", name="add_users")
     */
    public function addUser(): Response
    {
       return $this->render('user/add.html.twig');
    }


    /**
     * @Route("/users/save", methods={"POST"}, name="save_users")
     */
    public function saveUser(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $user = new User();

        $user->setLastName($request->request->get('last_name'));
        $user->setFirstName($request->request->get('first_name'));
        $user->setEmail($request->request->get('email'));

        $entityManager->persist($user);

        $entityManager->flush();

        $this->addFlash('success', 'Nouvel utilisateur créé');
       
        return $this->redirectToRoute('users');
    }


    /**
     * @Route("/users/edit/{id}", methods={"GET"}, name="edit_users")
     */
    public function editUser(User $user)
    {
        return $this->render('user/edit.html.twig',[
           
            'userId' => $user->getId(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/delete/{id}", methods={"GET"}, name="delete_users")
     */

    public function deleteUser(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('users');
    }


    /**
     * @Route("/users/update/{id}", methods={"POST"}, name="update_users")
     */

    public function updateUser (ManagerRegistry $doctrine, Request $request, User $user )
    {
        $entityManager = $doctrine->getManager();

        $user->setLastName($request->request->get('last_name'));
        $user->setFirstName($request->request->get('first_name'));
        $user->setEmail($request->request->get('email'));

        $entityManager->persist($user);

        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur mis à jour');
       
        return $this->redirectToRoute('users');
    }




}
