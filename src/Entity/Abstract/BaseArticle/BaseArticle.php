<?php

declare(strict_types=1);

namespace App\Entity\Abstract\BaseArticle;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Articles\Book\Book;
use ApiPlatform\Metadata\ApiFilter;
use App\Filters\AllBookSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use App\Entity\Abstract\BaseVariant\BaseVariant;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseArticle\BaseArticleInterface;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;

#[ORM\Entity()]
#[ORM\Table('shop_product')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(['Book' => Book::class])]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article', 'read:article', 'read:bookVariant', 'read:baseVariant']],
    denormalizationContext: ['groups' => ['write:books']],
    paginationEnabled: true,
    paginationItemsPerPage: 6
)]
#[ApiFilter(SearchFilter::class, properties: ["format" => "exact"])]
#[ApiFilter(AllBookSearchFilter::class, properties: ["format"])]
abstract class BaseArticle implements BaseArticleInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[Groups(['read:article', 'write:article',])]
    private int $id;

    #[ORM\Column(name: 'title', length: 255)]
    #[Groups(['read:article', 'write:article', 'read:order'])]
    private string $title;

    #[ORM\Column(name: 'resume', length: 500)]
    #[Groups(['read:article', 'write:article',])]
    private string $resume;

    #[ORM\Column(name: 'image', length: 255)]
    #[Groups(['read:article', 'write:article', 'read:order'])]
    private ?string $image;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get the value of resume
     *
     * @return string
     */
    public function getResume(): string
    {
        return $this->resume;
    }

    /**
     * Set the value of resume
     *
     * @param string $resume
     *
     * @return self
     */
    public function setResume(string $resume): void
    {
        $this->resume = $resume;
    }

    /**
     * Get the value of image
     *
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param string $image
     *
     * @return self
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

        /**
     * @return ?Collection<int, variant>
     */
    abstract function getVariants(): ?Collection;

    abstract function addVariant(BaseVariantInterface $variant): void;

    abstract function removeVariant(BaseVariantInterface $variant): void;

    public function __toString(): string
    {
        return $this->title;
    }
}