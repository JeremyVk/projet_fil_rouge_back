<?php

namespace App\Entity\Variants;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Abstract\BaseArticle\BaseArticleInterface;
use App\Entity\Abstract\BaseVariant\BaseVariant;
use App\Entity\Articles\Book\Book;
use App\Repository\BookVariantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Formats\Interfaces\FormatInterface;
use App\Entity\Formats\BookFormat;

#[ORM\Entity(repositoryClass: BookVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant', 'read:baseVariant', 'read:article']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:baseVariant']],
)]
class BookVariant extends BaseVariant
{
    #[ORM\Column(name: 'isbn_number', nullable: false)]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private int $isbnNumber;

    #[ORM\ManyToOne(targetEntity: BookFormat::class)]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private FormatInterface $format;

    #[ORM\ManyToOne(targetEntity:Book::class, inversedBy:'variants')]
    #[Groups(['read:baseVariant', 'write:baseVariant', "read:article", "write:article"])]
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

    public function getformat(): FormatInterface
    {
        return $this->format;
    }

    public function setFormat(FormatInterface $format)
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


    public function __toString(): string
    {
        return $this->getParent()->getTitle() . ' ' .   $this->format->getName();
    }
}
