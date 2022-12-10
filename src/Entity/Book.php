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
    normalizationContext: ['groups' => ['read:article', 'read:article', 'read:bookVariant', 'read:baseVariant']],
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
    #[Groups(['read:article', 'read:baseVariant'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, name: 'editor')]
    #[Groups(['read:article', 'write:books', 'read:baseVariant'])]
    private ?string $editor = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: BookVariant::class, orphanRemoval: true, cascade: ['persist'])]
    protected Collection $variants;

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


}
