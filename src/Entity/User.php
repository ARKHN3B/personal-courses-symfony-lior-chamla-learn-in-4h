<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="L'email que vous avez sélectionné existe déjà."
 * ) // Cette contrainte force cette entité à être unique sur certains champs
 */
class User implements UserInterface {
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\Email() // On contraint ce champ à être un email
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire minimum 8 caractères")
   */
  private $password;

  /**
   * On ne lie pas ce champ avec la base de données
   * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe doit être le même")
   */
  private $confirm_password;

  public function getId(): ?int {
    return $this->id;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getUsername(): ?string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): ?string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getConfirmPassword() {
    return $this->confirm_password;
  }

  /**
   * @param mixed $confirm_password
   */
  public function setConfirmPassword($confirm_password): void {
    $this->confirm_password = $confirm_password;
  }

  public function getRoles(): array {
    // TODO: Implement getRoles() method.
    return ["USER_ROLE"];
  }

  public function getSalt() {
    // TODO: Implement getSalt() method.
  }

  public function eraseCredentials() {
    // TODO: Implement eraseCredentials() method.
  }
}
