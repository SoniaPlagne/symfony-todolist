<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;

use App\Repository\TaskRepository;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("projects/{id}/tasks", name="tasks")
     */
    public function tasks(Project $project): Response
    {
        $tasks=$project->getTasks();

        return $this->render('task/list.html.twig', [
            'tasks'=>$tasks,
            'projects'=>$project,
        ]);
    }

    /**
     * @Route("projects/{id}/tasks/add", name="add_tasks")
     */
    public function addTasks():Response
    {
        return $this->render ('task/add.html.twig');
    }




}
