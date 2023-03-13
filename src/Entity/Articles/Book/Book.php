<?php

namespace App\Entity\Articles\Book;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Entity\Abstract\BaseArticle\BaseArticle;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Filters\AllBookSearchFilter;
use App\Entity\Abstract\BaseVariant\BaseVariant;
use App\Entity\Variants\BookVariant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


// #[ApiFilter(AllBookSearchFilter::class, properties: ["title" => "partial", "resume" => "partial", "editor"])]

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article', 'read:bookVariant', 'read:baseVariant']],
    denormalizationContext: ['groups' => ['write:books']],
    order: [ 'variants.stock' => 'DESC', 'variants.unitPrice' => 'ASC'],
    paginationEnabled: true,
    paginationItemsPerPage: 6
)]
class Book extends BaseArticle
{
    #[ORM\Column(length: 255, name: 'editor')]
    #[Groups(['read:article', 'write:books', 'read:baseVariant', 'read:order'])]
    private ?string $editor = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: BookVariant::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups(['read:article', 'write:article'])]
    private Collection $variants;

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

    public function addVariant(BaseVariantInterface $variant): void
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setParent($this);
        }
    }

    public function removeVariant(BaseVariantInterface $variant): void
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
        }
    }
}
