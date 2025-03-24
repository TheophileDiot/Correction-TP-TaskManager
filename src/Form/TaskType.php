<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire de gestion des tâches
 * 
 * Cette classe définit le formulaire utilisé pour créer et modifier des tâches.
 * Elle contient les champs titre, description et statut (terminé ou non).
 */
class TaskType extends AbstractType
{
  /**
   * Constantes pour les noms des champs du formulaire
   */
  public const FORM_TITLE = 'title';         // Champ pour le titre
  public const FORM_DESCRIPTION = 'description'; // Champ pour la description
  public const FORM_IS_DONE = 'isDone';      // Champ pour l'état de la tâche

  /**
   * Construction du formulaire avec tous les champs et leurs contraintes
   * 
   * @param FormBuilderInterface $builder Le constructeur de formulaire
   * @param array $options Options du formulaire
   */
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add(self::FORM_TITLE, TextType::class, [
        'label' => 'Titre',
        'attr' => [
          'placeholder' => 'Entrez le titre de la tâche',
          'class' => 'form-control'
        ],
        'constraints' => [
          new NotBlank(['message' => 'Le titre ne peut pas être vide']),
          new Length([
            'min' => 2,
            'max' => 255,
            'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
            'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères'
          ])
        ]
      ])
      ->add(self::FORM_DESCRIPTION, TextareaType::class, [
        'label' => 'Description',
        'required' => false,
        'attr' => [
          'placeholder' => 'Entrez une description (optionnel)',
          'class' => 'form-control',
          'rows' => 5
        ]
      ])
      ->add(self::FORM_IS_DONE, CheckboxType::class, [
        'label' => 'Tâche terminée',
        'required' => false,
        'attr' => [
          'class' => 'form-check-input'
        ],
        'label_attr' => [
          'class' => 'form-check-label'
        ]
      ])
    ;
  }

  /**
   * Configuration des options du formulaire
   * 
   * @param OptionsResolver $resolver Le résolveur d'options
   */
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Task::class,               // Classe de données associée
      'csrf_protection' => true,                 // Protection CSRF activée
      'csrf_field_name' => '_token',             // Nom du champ CSRF
      'csrf_token_id' => 'task_form'             // ID du token CSRF
    ]);
  }
}
