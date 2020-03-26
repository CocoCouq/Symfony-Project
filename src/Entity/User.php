<?php

namespace App\Entity;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
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
     * @Assert\Regex("/^[\w\s\.\!\'\-\+\=\$\€\%\&éèêëûüùîïíôöœàáâæ]+$/",
     *     message="Vous utilisez des caractères interdits")
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = 'ROLE_EMAIL';

    /**
     * @var string The hashed password
     * @Assert\NotNull(
     *     message="Vos mots de passes ne sont pas identiques"
     * )
     * @Assert\NotEqualTo("complex_password",
     *     message="Votre mot de passe n'est pas assez complexe"
     * )
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide")
     * @ORM\Column(type="string", length=80)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_active = false;


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        return [$roles];

    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setRolesValid(): self
    {
        $this->roles = 'ROLE_USER';

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = ($password == $_POST['pwd_conf'])
            ? ((preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[#$^+=!*()@%&.]).{8,}$/', $password))
                ? password_hash($password, PASSWORD_ARGON2ID)
                : 'complex_password')
            : null;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getUserToken(): ?string
    {
        return $this->user_token;
    }

    public function setUserToken(?string $user_token): self
    {
        $this->user_token = bin2hex(random_bytes(6)).$user_token.bin2hex(random_bytes(6));
        return $this;
    }

    public function getUserActive(): ?bool
    {
        return $this->user_active;
    }

    public function setUserActive(bool $user_active): self
    {
        $this->user_active = $user_active;

        return $this;
    }
}
