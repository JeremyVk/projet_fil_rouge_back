<?php

namespace App\Entity\Formats;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Formats\Interfaces\FormatInterface;
use App\Repository\BookFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Variants\BookVariant;

#[ORM\Entity(repositoryClass: BookFormatRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookFormats', 'read:books']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:bookFormats']],
)]
class BookFormat implements FormatInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:bookFormats', 'read:bookVariant', 'read:books'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:bookFormats', 'read:bookVariant', 'read:books'])]
    private ?string $name = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
