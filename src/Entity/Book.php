<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=10},
 *     denormalizationContext={"groups"={"book:create"}},
 *     normalizationContext={"groups"={"book:read"}}
 * )
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @Groups({"book:read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     min = 4,
     *     max = 255,
     *     minMessage = "Le Title fait au minimum {{ limit }} caracteres.",
     *     maxMessage = "Le Title fait au maximum {{ limit }} caracteres."
     * )
     * @Groups({"book:create", "book:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @Groups({"book:read"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @Groups({"book:create", "book:read"})
     * @ORM\Column(type="date", nullable=true)
     */
    private $releaseDate;

    /**
     * @Assert\Length(
     * min = 13,
     * max = 17,
     * minMessage = "un code isbn fait au minimum {{ limit }} caracteres.",
     * maxMessage = "un code isbn fait au maximum {{ limit }} caracteres."
     * )
     * @Groups({"book:create", "book:read"})
     * @ORM\Column(type="string", length=17, nullable=true)
     */
    private $isbn;

    /**
     * @Groups({"book:read"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups({"book:read"})
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @Groups({"book:create", "book:read"})
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="books")
     */
    private $tags;

    /**
     * @Groups({"book:create", "book:read"})
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     */
    private $categories;

    /**
     * @Groups({"book:create", "book:read"})
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     */
    private $authors;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        // $arr = array('yolo', 'salut', 'peutetre');
        // var_dump($arr);
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

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

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }
}
