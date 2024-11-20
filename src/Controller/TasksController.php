<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/tasks')]
class TasksController extends AbstractController
{
    #[Route('/', name: 'app_tasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository, UserRepository $usersRepository): Response
    {
        $user = $this->getUser();
        $tasks = [];

        if ($user) {
            $useremail = $user->getUserIdentifier();
            $userEntity = $usersRepository->findOneBy(['email' => $useremail]);

            if ($userEntity) {
                if (in_array('ROLE_ADMIN', $userEntity->getRoles())) {
                    $tasks = $tasksRepository->findAll();
                } else {
                    $tasks = $tasksRepository->findByUserId($userEntity->getId());
                }
            }
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $task = new Tasks();
    $form = $this->createForm(TasksType::class, $task);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($task);
        $entityManager->flush();

        if ($task->getUser()) {
            $email = $task->getUser()->getEmail();
        } else {
            $email = 'No user assigned';
        }

        $this->addFlash('success', "Task created and assigned to: $email");

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('tasks/new.html.twig', [
        'task' => $task,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles());

    $form = $this->createForm(TasksType::class, $task, ['is_admin' => $isAdmin]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      
        if (!$isAdmin) {
            $task->setTask($task->getTask()); 
            $task->setDateR($task->getDateR());
            $task->setTypeT($task->getTypeT());
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('tasks/edit.html.twig', [
        'task' => $task,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }
}
