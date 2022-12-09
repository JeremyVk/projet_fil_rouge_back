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
    order: ['variants.unitPrice' => 'ASC']
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
    private Collection $variants;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
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
     * @return ?Collection<int, variant>
     */
    public function getVariants(): ?Collection
    {
        return $this->variants;
    }

    public function addVariant(BookVariant $variant): void
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setBook($this);
        }
    }

    public function removeVariant(BookVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
            if ($variant->getBook() === $this) {
                $variant->setBook(null);
            }
        }

        return $this;
    }
}
