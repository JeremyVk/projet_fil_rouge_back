<?php

namespace App\Entity\Variants;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Abstract\BaseVariant\BaseVariant;
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
    private string $isbnNumber;

    #[ORM\ManyToOne(targetEntity: BookFormat::class)]
    #[Groups(['read:article', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private FormatInterface $format;

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
}
