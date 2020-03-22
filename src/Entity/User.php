<?php

namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="Cet email est déjà utilisé")
 * @UniqueEntity("username", message="Ce nom d'utilisateur est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(message="Ceci n'est pas un email Valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4", minMessage="Votre pseudo doit avoir minimum 4 caractères", max="16", maxMessage="votre pseudo doit avoir maximum 16 caractères")     
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Voter mot de passe doit avoir minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Les mots de passe doivent correspondre")
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe doivent correspondre")
     */
    public $confirm_password;

    /**
     * @Assert\Length(min="8", minMessage="Voter mot de passe doit avoir minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_newpassword", message="Les mots de passe doivent correspondre")
     */
    public $newpassword;

    /**
     * @Assert\EqualTo(propertyPath="newpassword", message="Les mots de passe doivent correspondre")
     */
    public $confirm_newpassword;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newsletter;

    public function setRoles()
    {
        $this->roles = array('ROLE_USER');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }
}
