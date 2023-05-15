<?php

namespace App\Entity\Articles\Book;

use App\Entity\Author\Author;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Entity\Abstract\BaseArticle\BaseArticle;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Variants\BookVariant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


// #[ApiFilter(AllBookSearchFilter::class, properties: ["title" => "partial", "resume" => "partial", "editor"])]

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article', 'read:bookVariant', 'read:baseVariant', 'read:books', 'read:book']],
    denormalizationContext: ['groups' => ['write:books', "write:book"]],
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

    #[ORM\ManyToMany(targetEntity: Author::class, mappedBy: 'books', cascade: ['persist'])]
    #[Groups(['read:article'])]
    private Collection $authors;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->authors = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * @param Collection $authors
     */
    public function setAuthors(Collection $authors): void
    {
        $this->authors = $authors;
    }

    public function addAuthor(Author $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }
    }

    public function removeAuthor(Author $author): void
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
            // set the owning side to null (unless already changed)
        }
    }

}
