<?php

declare(strict_types=1);

namespace App\Entity\Abstract\BaseArticle;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Abstract\BaseArticle\BaseArticleInterface;

#[MappedSuperclass()]
abstract class BaseArticle implements BaseArticleInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[Groups(['read:article', 'write:article',])]
    private int $id;

    #[ORM\Column(name: 'title', length: 255)]
    #[Groups(['read:article', 'write:article', ])]
    private string $title;

    #[ORM\Column(name: 'resume', length: 500)]
    #[Groups(['read:article', 'write:article',])]
    private string $resume;

    #[ORM\Column(name: 'image', length: 255)]
    #[Groups(['read:article', 'write:article', ])]
    private string $image;

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
    public function getImage(): string
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
    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}