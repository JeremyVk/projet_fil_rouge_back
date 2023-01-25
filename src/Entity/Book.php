<?php

namespace App\Entity;

use App\Entity\BookVariant;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Abstract\BaseArticle;
use App\Entity\Abstract\BaseVariant;
use App\Filters\AllBookSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
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
    #[ORM\Column(length: 255, name: 'editor')]
    #[Groups(['read:article', 'write:books', 'read:baseVariant'])]
    private ?string $editor = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: BookVariant::class, orphanRemoval: true, cascade: ['persist'])]
    protected Collection $variants;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
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

    public function addVariant(BaseVariant $variant): void
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setParent($this);
        }
    }

    public function removeVariant(BaseVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
        }
        return $this;
    }

}
