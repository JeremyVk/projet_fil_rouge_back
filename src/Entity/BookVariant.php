<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Abstract\BaseArticle;
use App\Abstract\BaseVariant;
use App\Repository\BookVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant', 'read:baseVariant', 'read:article']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:baseVariant']],
)]
class BookVariant extends BaseVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:article', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private ?int $id = null;

    #[ORM\Column(name: 'isbn_number', nullable: false)]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private string $isbnNumber;

    #[ORM\ManyToOne(targetEntity: BookFormat::class, inversedBy: 'books')]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private BookFormat $format;

    #[ORM\ManyToOne(targetEntity:Book::class, inversedBy:'variants')]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant', 'read:baseVariant', 'read:article'])]
    protected BaseArticle $parent;

    public function getId(): ?int
    {
        return $this->id;
    }

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
}
