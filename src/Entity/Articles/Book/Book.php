<?php

namespace App\Entity\Articles\Book;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Abstract\BaseArticle\BaseArticle;
use App\Filters\AllBookSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;
use App\Entity\Variants\BookVariant;


// #[ApiFilter(AllBookSearchFilter::class, properties: ["title" => "partial", "resume" => "partial", "editor"])]

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article', 'read:bookVariant', 'read:baseVariant']],
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
