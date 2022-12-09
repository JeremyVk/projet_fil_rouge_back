<?php

namespace App\Entity;

use App\Abstract\BaseArticle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Filters\AllBookSearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;


// #[ApiFilter(AllBookSearchFilter::class, properties: ["title" => "partial", "resume" => "partial", "editor"])]

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:books', 'read:article']],
    denormalizationContext: ['groups' => ['write:books']],
)]
#[ApiFilter(SearchFilter::class, properties: ["format" => "exact"])]
#[ApiFilter(AllBookSearchFilter::class, properties: ["title", "resume", "editor"])]
class Book extends BaseArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:books'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'editor')]
    #[Groups(['read:books', 'write:books'])]
    private ?string $editor = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookVariant::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups(['read:books', 'write:books'])]
    private ?Collection $bookVariants;

    public function __construct()
    {
        $this->bookVariants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(string $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * @return ?Collection<int, BookVariant>
     */
    public function getBookVariants(): ?Collection
    {
        return $this->bookVariants;
    }

    public function addBookVariant(BookVariant $bookVariant): void
    {
        if (!$this->bookVariants->contains($bookVariant)) {
            $this->bookVariants->add($bookVariant);
            $bookVariant->setBook($this);
        }
    }

    public function removeBookVariant(BookVariant $bookVariant): self
    {
        if ($this->bookVariants->removeElement($bookVariant)) {
            // set the owning side to null (unless already changed)
            if ($bookVariant->getBook() === $this) {
                $bookVariant->setBook(null);
            }
        }

        return $this;
    }
}
