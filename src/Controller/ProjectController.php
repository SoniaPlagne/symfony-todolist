<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;

use DateTime;

use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects=$projectRepository->findAll();

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
    public function saveProject(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator, LoggerInterface $logger): Response
    {
        $entityManager = $doctrine->getManager();
        
        $project = new Project();

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $project->setStartDateStr($request->request->get('start_date'));
        $project->setEndDateStr($request->request->get('end_date'));

        $logger->debug('Erreurs save Projet',
        [
            'name'=>$request->request->get('name'),
            'description'=>$request->request->get('description'),
            'start_date'=>$request->request->get('start_date'),
            'end_date'=>$request->request->get('end_date'),
        ]);


        $errors = $validator->validate($project);

        if (count($errors) > 0) 
        {
            /*
            * Uses a __toString method on the $errors variable which is a
            * ConstraintViolationList object. This gives us a nice string
            * for debugging.
            */
            $errorsString = (string) $errors;
            
            $this->addFlash('error', $errorsString);

            return $this->redirectToRoute('add_projects');
        }

        $startDate = new DateTime($request->request->get('start_date'));
        $endDate = new DateTime($request->request->get('end_date'));

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

    public function updateProject (ManagerRegistry $doctrine, Request $request, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $project->setName($request->request->get('name'));
        $project->setDescription($request->request->get('description'));
        $project->setStartDate(new \Datetime($request->request->get('start-date')));
        $project->setEndDate(new \DateTime($request->request->get('end_date')));

        $entityManager->persist($project);
        $entityManager->flush();

        $this->addFlash('success', 'Projet mis à jour');
    
        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/projects/{id}/users", methods={"GET"}, name="users_project")
     */

    public function usersProject (UserRepository $userRepository, Project $project):Response
    {
        $users=$userRepository->findAll();

        $projectUsers = $project->getUsers();

        return $this-> render ('project/users.html.twig', 
        [
            'users'=>$users,
            'project'=>$project,
            'projectUsers'=>$projectUsers
        ]);

    }

    /**
     * @Route("/projects/{id}/users/save", methods={"POST"}, name="save_users_project")
     */
    public function saveUsersProject(UserRepository $userRepository,ManagerRegistry $doctrine, Request $request, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $project->clearUsers();

        $listIdUser = $request->request->get('user_id', []);

        foreach($listIdUser as $userId) 
        {
            $user = $userRepository->find($userId);
            $project->addUser($user);
        }

        $entityManager->persist($project);
        $entityManager->flush();

        $this->addFlash('success', 'Un ou plusieurs utilisateurs ont été ajoutés au projet');
    
        return $this->redirectToRoute('projects');
    }


}
