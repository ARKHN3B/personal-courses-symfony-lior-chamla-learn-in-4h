<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController {
  /**
   * @Route("/inscription", name="registration")
   *
   * @param Request                      $request
   * @param EntityManagerInterface       $manager
   * @param UserPasswordEncoderInterface $encoder
   *
   * @return Response
   *                 // On utilise la classe UserPasswordEncoderInterface pour encoder les mots de passe de nos
   *                 utilisateurs
   */
  public function registration(Request $request, EntityManagerInterface $manager,
                               UserPasswordEncoderInterface $encoder): Response {
    $user = new User();
    $form = $this->createForm(RegistrationType::class,
                              $user); // On crée un formulaire et on lie les champs de notre nouvel utilisateur

    $form->handleRequest($request); // On lie la requête à notre formulaire

    if ($form->isSubmitted() && $form->isValid()) {
      // Via le fichier config/packages/security.yaml, sur le champ encoder, la fonction va connaître les spécificités de l'encodeur (sha256, sha512, etc)
      $hash = $encoder->encodePassword($user, $user->getPassword());
      $user->setPassword($hash);
      $manager->persist($user);
      $manager->flush();

      return $this->redirectToRoute("login");
    }

    return $this->render('security/registration.html.twig',
                         [
                           "form" => $form->createView(),
                         ]);
  }

  /**
   * @Route("/connexion", name="login")
   *
   * @return Response
   */
  public function login(): Response {
    return $this->render("security/login.html.twig");
  }

  /**
   * @Route("/deconnexion", name="logout")
   *
   * @return Response
   */
  public function logout(): Response {
    // Cette route ne fait rien. En effet, c'est le composant de sécurité au travers de la config qui va s'en charger
  }
}
