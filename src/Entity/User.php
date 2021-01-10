<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource()
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={"username": "exact"}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     min = 3,
     *     max = 180,
     *     minMessage = "Votre username fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Votre username fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Assert\NotBlank(message="Le champ username est obligatoire.")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank(message="Le champ password est obligatoire.")
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Length(
     *     min = 6,
     *     max = 128,
     *     minMessage = "Votre email fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Votre email fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Assert\Email(message="La syntaxe de votre email n'est pas reconnue.")
     * @Assert\NotBlank(message="Le champ email est obligatoire.")
     * @ORM\Column(type="string", length=128)
     */
    private $email;

    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 128,
     *     minMessage = "Votre firstname fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Votre firstname fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Assert\NotBlank(message="Le champ firstname est obligatoire.")
     * @ORM\Column(type="string", length=128)
     */
    private $firstname;

    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 128,
     *     minMessage = "Votre lastname fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Votre lastname fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Assert\NotBlank(message="Le champ lastname est obligatoire.")
     * @ORM\Column(type="string", length=128)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
        $this->password = $password;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
