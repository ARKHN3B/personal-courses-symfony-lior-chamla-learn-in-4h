<?php

namespace App\Controller;

use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController {

  /**
   * @Route("/", name="root")
   * @return Response
   */
  public function root(): Response {
    return $this->render("blog/home.html.twig",
                         [
                           "title" => "Bienvenue sur ce super blog l'ami !",
                           "age"   => 19,
                         ]);
  }

  /**
   * @Route("/blog", name="blog")
   * @param ArticleRepository $repo
   *
   * @return Response
   */
  public function index(ArticleRepository $repo): Response { // injection de la dépendance ArticleRepository via le ServiceContainer
    $articles = $repo->findAll();

    return $this->render('blog/index.html.twig',
                         [
                           'controller_name' => 'BlogController',
                           "articles"        => $articles,
                         ]);
  }


  /**
   * NOTE : on peut créer un formulaire de manière automatique avec la commande php bin/console make:form
   *        La convention veut que la classe form créée se termine par le mot clé Type, i.e. ArticleType
   *
   * @Route("/blog/new", name="blog_create")
   * @Route("/blog/{id}/edit", name="blog_edit") // Possibilité d'avoir de multiples routes
   *
   * @param Article|null           $article
   * @param Request                $request
   * @param EntityManagerInterface $manager
   *
   * @return Response
   */
  public function form(Article $article = null, Request $request, EntityManagerInterface $manager): Response {
    $isNew = !$article || !$article->getId();

    if ($isNew) $article = new Article(); // si il n'y a pas d'article, on en crée un nouveau

//    $form = $this->createFormBuilder($article)
//                 ->add("title")
//                 ->add("content")
//                 ->add("image")
//                 ->getForm();

    $form = $this->createForm(ArticleType::class, $article); // Formulaire créé par la console

    $form->handleRequest($request); // permet de lier les valeurs de l'article aux valeurs de la requête

    // Si le formulaire est soumis et que les champs sont valides
    if ($form->isSubmitted() && $form->isValid()) {

      // Si l'article n'a pas d'identifiant, alors c'est un nouvel article et on doit créer une date de création
      if ($isNew) {
        $article->setCreatedAt(new \DateTime());
      }

      $manager->persist($article); // Persistance de l'objet article
      $manager->flush(); // aggrémentation de la base de données

      return $this->redirectToRoute("blog_show", ["id" => $article->getId()]); // On redirige sur la vue de l'article
    }

    return $this->render("blog/create.html.twig", [
      "formArticle" => $form->createView(),
      "editMode" => !$isNew,
    ]);
  }


  /**
   * OLD ROUTE BUT INFORMATIVE
   *
   * @Route("/blog/new", name="old_blog_create_1")
   *
   * ATTENTION : l'ordre des routes (i.e. des fonctions) dans ce contrôleur à son importance !
   *
   * @return Response
   */
  public function create(): Response {
    $article = new Article();

    $form = $this->createFormBuilder($article) // créer un Objet Complexe formulaire lié avec une entité
                 ->add( // la fonction add ajoute des champs au formulaire lié à un champ d'une entité
             "title"
                 )
                 ->add(
             "content",
             TextType::class // le second champs permet de changer le type de champs
                 )
                 ->add(
             "image",
              null,
                    [ // le troisième arguments de la fonction add() permet de passer certaines options
                        "attr"  => [ // ici la clé attr permet de passer les attributs sur mon champ de formulaire
                            "placeholder" => "URL de l'image",
                            "class"       => "form-control",
                        ],
                        "label" => "Votre image :",
                    ]
                 )
//                Ajout d'un bouton submit
//                 ->add("submit", SubmitType::class, [
//                   "attr" => [
//                     "label" => "Sauvegarder",
//                     "class" => "form-control",
//                   ]
//                 ])
                 ->getForm(); // retourne le résultat final, i.e. le formaulaire sous forme d'Objet Complexe

    return $this->render("create.old.html.twig",
                         [
                           "formArticle" => $form->createView(),
                           // retourne un objet représentant l'aspect "affichage" du formulaire
                         ]);
  }

  /**
   * @Route("/blog/article/{id}", name="blog_show")
   *
   * @param Article $article
   *
   * @return Response
   */
  // public function show(ArticleRepository $repo, $id): Response {
  public function show(Article $article): Response { // ParamConverter est capable d'aller chercher l'article avec le champs (ici id)
    // $article = $repo->find($id);
    return $this->render("blog/show.html.twig",
                         [
                           "article" => $article,
                         ]);
  }
}
