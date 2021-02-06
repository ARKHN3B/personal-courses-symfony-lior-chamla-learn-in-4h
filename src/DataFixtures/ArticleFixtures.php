<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;

class ArticleFixtures extends Fixture {

  //  public function load(ObjectManager $manager) {
  //    for ($i = 0 ; $i < 10 ; $i++) {
  //      $article = new Article();
  //      $article->setTitle("Titre de l'article numéro $i");
  //      $article->setContent("The anomaly is a gravimetric nanomachine.") // on peut enchaîner les méthodes générées car le contexte (this) est retourné.
  //              ->setImage("https://place-hold.it/350x120")
  //              ->setCreatedAt(new \DateTime()); // L'anti-slash définit un namespace global de PHP puisque toutes les classes dans Symfony doivent faire partie d'un espace de nom
  //
  //      $manager->persist($article); // Permet de conserver l'article en mémoire dans le manager ORM
  //    }
  //
  //    $manager->flush(); // Envoie la requête SQL
  //  }

  public function load(ObjectManager $manager) {

    $faker = Factory::create("fr_FR"); // Permet d'utiliser la librairy Faker qui nous permet de générer de fausses données

    // 1) Créer 3 catégories fakées

    for ($i = 0 ; $i < 3 ; $i++) {
      $category = new Category();
      $category->setTitle($faker->sentence())
               ->setDescription($faker->paragraph());

      $manager->persist($category); // Persiste notre nouvelle catégorie dans notre mémoire ORM

      // 2) Créer entre 4 et 6 articles fakées

      // La fonction PHP native md_rand permet de générer aléatoirement un nombre parmis un intervalle
      for ($j = 0 ; $j < mt_rand(4, 6) ; $j++) {
        $article = new Article();

        $content = implode(" ", $faker->paragraphs(5)); // Création d'un contenu

        $article->setTitle($faker->sentence());
        $article->setContent($content)
                ->setImage($faker->imageUrl())
                ->setCreatedAt($faker->dateTimeBetween("-6 months"))
                ->setCategory($category);

        $manager->persist($article);

        // 3) Créer entre 4 et 10 commentaires

        for ($k = 0 ; $k < mt_rand(4, 10) ; $k++) {
          $comment = new Comment();

          $content = implode(" ", $faker->paragraphs(2)); // Création d'un contenu

          $now          = new \DateTime();
          $dateInterval = $now->diff($article->getCreatedAt());
          $days         = $dateInterval->days;   // nombre de jours d'interval
          $minDate      = "-" . $days . " days"; // e.g. -100 days

          $comment->setAuthor($faker->name)
                  ->setContent($content)
                  ->setCreatedAt($faker->dateTimeBetween($minDate))
                  ->setArticle($article);

          $manager->persist($comment);
        }
      }
    }

    $manager->flush(); // Envoie la requête SQL
  }
}
