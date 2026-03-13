<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class TaskController extends AbstractController
{
    #[Route(name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository, Request $request): Response
    {

        $status = $request->query->get("status");
        $title = $request->query->get("title");

        $tasks = $status ? $taskRepository->findBy(['status' => $status]) : $taskRepository->findAll();

        $q = $request->query->get("q");

        if ($q) {
            $tasks = $taskRepository->findby(['title' => $title]);
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'status' => $status,
        ]);
    }

    #[Route(name: 'task_search', methods: ['POST', 'GET'])]
    public function Search(TaskRepository $taskRepository, Request $request): Response
    {
        $query = $request->request->all('form')['query'];
        if ($query) {
            $articles = $taskRepository->findArticlesByName($query);
            return $this->render('task/index.html.twig', [
                'articles' => $articles
            ]);
        } else {
            return $this->redirectToRoute('task_index');
        }
    }


    #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {

        if ($task->getStatus() === "IN_PROGRESS") {
            $task->setStatus("COMPLETED");
        } else {
            $task->setStatus("IN_PROGRESS");
        }

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json([
            "success" => true,
            "message" => "Task changed status"
        ]);
    }
}
