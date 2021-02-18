<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('email')
            ->add('username')
            // On vient dire que ce champ est de type mot de passe
            ->add('password', PasswordType::class)
            // On ajoute manuellement un nouveau champ pour avoir la confirmation du mot
            // de passe (on doit aussi le rajouter dans l'entité sans le lier à la base
            // de données)
            ->add("confirm_password", PasswordType::class);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
                             'data_class' => User::class,
                           ]);
  }
}
