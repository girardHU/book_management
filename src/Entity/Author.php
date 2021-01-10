<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=10},
 *     denormalizationContext={"groups"={"author:create"}},
 *     normalizationContext={"groups"={"author:read"}}
 * )
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
{
    /**
     * @Groups({"author:read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     min = 4,
     *     max = 128,
     *     minMessage = "Le firstname fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Le firstname fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Groups({"author:read", "author:create"})
     * @ORM\Column(type="string", length=128)
     */
    private $firstname;

    /**
     * @Assert\Length(
     *     min = 4,
     *     max = 128,
     *     minMessage = "Le lastname fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Le lastname fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Groups({"author:read", "author:create"})
     * @ORM\Column(type="string", length=128)
     */
    private $lastname;

    /**
     * @Groups({"author:read"})
     * @ORM\Column(type="string", length=256, unique=true)
     */
    private $slug;

    /**
     * @Assert\Length(
     *     min = 4,
     *     max = 128,
     *     minMessage = "Le country fait {{ value }} caracteres quand le minimum est {{ limit }}.",
     *     maxMessage = "Le country fait {{ value }} caracteres quand le maximum est {{ limit }}."
     * )
     * @Groups({"author:read", "author:create"})
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $country;

    /**
     * @Groups({"author:read", "author:create"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="authors")
     */
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeAuthor($this);
        }

        return $this;
    }
}
