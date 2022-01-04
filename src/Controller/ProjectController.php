<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Entity\Project;


class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project")
     */
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

/**
 * @Route("/projects", name="projects")
 */
    public function projects(ProjectRepository $projectrepository): Response
    {
        $projects=$projectrepository->findAll();

        return $this->render('project/list.html.twig', ['projects'=>$projects]);
    }

/**
 * @Route("/projects/add", name="add_projects")
 */
    public function addProject(): Response
    {
        return $this->render ('project/add.html.twig');
    }

/**
 * @Route("/projects/save", methods={"POST"}, name="save_projects")
 */
    public function saveProject(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        

        $project = new Project();
        $startDate = new DateTime($request->request->get('start-date'));
        $endDate = new DateTime($request->request->get('end-date'));

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $project->setStartDate($startDate);
        $project->setEndDate($endDate);

        $entityManager->persist($project);

        $entityManager->flush();

        $this->addFlash('success', 'Nouveau projet créé');
       
        return $this->redirectToRoute('projects');
    }

/**
 * @Route("/projects/edit/{id}", methods={"GET"}, name="edit_projects")
 */
    public function editProject(Project $project)
    {
        return $this->render('project/edit.html.twig',[
           
            'Id' => $project->getId(),
            'project' => $project,
        ]);
    }


/**
 * @Route("/projects/delete/{id}", methods={"GET"}, name="delete_projects")
 */

    public function deleteProjects(ManagerRegistry $doctrine, Project $project)
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('projects');
    }


/**
     * @Route("/projects/update/{id}", methods={"POST"}, name="update_projects")
     */

    public function updateProject (ManagerRegistry $doctrine, Request $request, Project $project)
    {
        $entityManager = $doctrine->getManager();

        $startDate =new DateTime($request->request->get('start-date'));
        $endDate = new DateTime($request->request->get('end-date'));

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $project->setStartDate($startDate);
        $project->setEndDate($endDate);

        $entityManager->persist($project);

        $entityManager->flush();

        $this->addFlash('success', 'Projet mis à jour');
       
        return $this->redirectToRoute('projects');
    }

/**
 * @Route("/projects/{id}/users", methods={"GET"}, name="users_projects")
 */

    public function usersProject (UserRepository $userRepository)
    {
        $users=$userRepository->findAll();

        return $this-> render ('project/users.html.twig', ['users'=>$users]);

    }

/**
 * @Route("/projects/{id}/users/save", methods={"POST"}, name="save_users_projects")
 */
    public function saveUsersProject(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        

        $user_project = new UserProject();

        $entityManager->persist($project);

        $entityManager->flush();

        $this->addFlash('success', 'Un ou plusieurs utilisateurs ont été ajoutés au projet');
       
        return $this->redirectToRoute('projects');
    }


}
