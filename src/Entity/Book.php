<?php

namespace App\Entity;

use App\Abstract\Article;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:books', 'read:article']],
    denormalizationContext: ['groups' => ['write:books']],
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Book extends Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:books'])]
    private ?int $id = null;

    #[ORM\Column(name: 'isbn_number')]
    #[Groups(['read:books', 'write:books'])]
    private ?int $isbnNumber = null;

    #[ORM\Column(length: 255, name: 'format')]
    #[Groups(['read:books', 'write:books'])]
    private ?string $format = null;

    #[ORM\Column(length: 255, name: 'editor')]
    #[Groups(['read:books', 'write:books'])]
    private ?string $editor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbnNumber(): ?int
    {
        return $this->isbnNumber;
    }

    public function setIsbnNumber(int $isbnNumber): self
    {
        $this->isbnNumber = $isbnNumber;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
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
}
