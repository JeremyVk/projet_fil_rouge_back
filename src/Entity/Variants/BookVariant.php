<?php

namespace App\Entity\Variants;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Abstract\BaseArticle\BaseArticleInterface;
use App\Entity\Abstract\BaseVariant\BaseVariant;
use App\Repository\BookVariantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\BookFormat;
use App\Entity\Articles\Book\Book;

#[ORM\Entity(repositoryClass: BookVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant', 'read:baseVariant', 'read:article']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:baseVariant']],
)]
class BookVariant extends BaseVariant
{
    #[ORM\Column(name: 'isbn_number', nullable: false)]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private string $isbnNumber;

    #[ORM\ManyToOne(targetEntity: BookFormat::class, inversedBy: 'books')]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private BookFormat $format;

    #[ORM\ManyToOne(targetEntity:Book::class, inversedBy:'variants')]
    private BaseArticleInterface $parent;

    public function getIsbnNumber(): int
    {
        return $this->isbnNumber;
    }

    public function setIsbnNumber(int $isbnNumber): self
    {
        $this->isbnNumber = $isbnNumber;

        return $this;
    }

    public function getformat(): BookFormat
    {
        return $this->format;
    }

    public function setFormat(BookFormat $format)
    {
        $this->format = $format;
    }

    public function getParent(): BaseArticleInterface
    {
        return $this->parent;
    }

    public function setParent(BaseArticleInterface $parent): void
    {
        $this->parent = $parent;
    }
}