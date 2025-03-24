<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de gestion des tâches
 * 
 * Ce contrôleur gère toutes les opérations CRUD (Create, Read, Update, Delete)
 * pour les tâches, ainsi que le changement de statut (terminé/en cours).
 */
#[Route('/task')]
final class TaskController extends AbstractController
{
    /**
     * Constructeur avec injection des dépendances
     * 
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités pour la persistance
     * @param TaskRepository $taskRepository Repository pour les opérations sur les tâches
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaskRepository $taskRepository
    ) {
    }

    /**
     * Liste toutes les tâches avec options de tri
     * 
     * @param Request $request Requête HTTP
     * @return Response Page d'index avec la liste des tâches
     */
    #[Route('', name: 'app_task')]
    public function index(Request $request): Response
    {
        // Récupérer le paramètre de tri depuis la requête
        $sortBy = $request->query->get('sort');
        $tasks = $this->taskRepository->findAllSorted($sortBy);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'current_sort' => $sortBy,
        ]);
    }

    /**
     * Affiche les détails d'une tâche spécifique
     * 
     * @param Task $task La tâche à afficher (injection par paramconverter)
     * @return Response Page de détail de la tâche
     */
    #[Route('/{id}', name: 'app_task_show', requirements: ['id' => '\d+'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * Crée une nouvelle tâche
     * 
     * @param Request $request Requête HTTP
     * @return Response Formulaire de création ou redirection
     */
    #[Route('/new', name: 'app_task_new', priority: 2)]
    public function new(Request $request): Response
    {
        // Créer une nouvelle tâche
        $task = new Task();
        $task->setCreatedAt(new \DateTimeImmutable());
        $task->setIsDone(false);

        // Créer et traiter le formulaire
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister la tâche
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            // Message flash et redirection
            $this->addFlash('success', 'La tâche a été créée avec succès !');

            return $this->redirectToRoute('app_task_show', ['id' => $task->getId()]);
        }

        // Afficher le formulaire
        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Modifie une tâche existante
     * 
     * @param Request $request Requête HTTP
     * @param Task $task La tâche à modifier
     * @return Response Formulaire d'édition ou redirection
     */
    #[Route('/{id}/edit', name: 'app_task_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Task $task): Response
    {
        // Créer et traiter le formulaire
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications
            $this->entityManager->flush();

            // Message flash et redirection
            $this->addFlash('success', 'La tâche a été modifiée avec succès !');

            return $this->redirectToRoute('app_task_show', ['id' => $task->getId()]);
        }

        // Afficher le formulaire
        return $this->render('task/edit.html.twig', [
            'form' => $form,
            'task' => $task,
        ]);
    }

    /**
     * Supprime une tâche
     * 
     * @param Request $request Requête HTTP
     * @param Task $task La tâche à supprimer
     * @return Response Redirection vers la liste des tâches
     */
    #[Route('/{id}/delete', name: 'app_task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Task $task): Response
    {
        // Vérifier la validité du token CSRF
        if ($this->isCsrfTokenValid('delete-' . $task->getId(), $request->request->get('_token'))) {
            // Supprimer la tâche
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            // Message flash
            $this->addFlash('success', 'La tâche a été supprimée avec succès !');
        }

        // Redirection vers la liste des tâches
        return $this->redirectToRoute('app_task');
    }

    /**
     * Change le statut d'une tâche (terminée <-> en cours)
     * 
     * @param Task $task La tâche dont le statut doit être modifié
     * @return Response Redirection vers la page de détail de la tâche
     */
    #[Route('/{id}/toggle', name: 'app_task_toggle', requirements: ['id' => '\d+'])]
    public function toggle(Task $task): Response
    {
        // Inverser l'état de la tâche
        $task->setIsDone(!$task->isDone());
        $this->entityManager->flush();

        // Message flash
        $status = $task->isDone() ? 'terminée' : 'en cours';
        $this->addFlash('success', sprintf('La tâche a été marquée comme %s', $status));

        // Redirection vers la page de détail
        return $this->redirectToRoute('app_task_show', ['id' => $task->getId()]);
    }
}
