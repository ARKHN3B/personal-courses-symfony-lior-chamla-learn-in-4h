<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('title')
            ->add('content')
            ->add('image')
    /*
     * Attention !
     * Category est une relation, et en somme une entité.
     * Il est impossible de l'ajouter comme pour les éléments ci-dessus. Il faut donc préciser ce que représente ce champ.
     *
     * Pour cela, il faut utilise un champ de type spécifique aux entités : EntityType qui va nous permettre de rendre
     * ce champ avec divers tags HTML comme <select>, input:checkbox ou encore input:radio.
     *
     */
            ->add('category', EntityType::class, [ // 1) Passage d'un tableau d'options
               "class" => Category::class, // 2) Spécifie l'entité qui est concernée
               "choice_label" => "title" // 3) Le choix du label a utilisé dans notre "liste" HTML
            ])
         // ->add('createdAt') Retire manuellement
    ;
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
                             'data_class' => Article::class,
                           ]);
  }
}
