<?php

namespace AppBundle\Controller;

use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Task;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="list_task")
     */
    public function tasksAction()
    {

        return $this->render('AppBundle:Task:tasks.html.twig', array(
            // ...
        ));
    }


    /**
     * @Route("/task/new", name="new_task")
     */
    public function newAction(Request $request)
    {

        $task = new Task();

        $task->setTask('Write a blog post !');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            var_dump($task);
//            $this->redirectToRoute('task_success');
        }

        return $this->render('AppBundle:Task:new.html.twig', array(
            'form' => $form->createView()
        ));
    }


}
