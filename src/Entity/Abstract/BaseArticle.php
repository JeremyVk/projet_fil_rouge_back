<?php

declare(strict_types=1);

namespace App\Entity\Abstract;

use App\Entity\Abstract\BaseVariant;;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[MappedSuperclass()]
abstract class BaseArticle
{
    #[ORM\Column(name: 'title', length: 255)]
    #[Groups(['read:article', 'write:article', ])]
    protected string $title;

    #[ORM\Column(name: 'resume', length: 500)]
    #[Groups(['read:article', 'write:article',])]
    protected string $resume;

    #[ORM\Column(name: 'image', length: 255)]
    #[Groups(['read:article', 'write:article', ])]
    protected string $image;

    #[Groups(['read:article', 'write:article'])]
    protected Collection $variants;

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
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
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
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function addVariant(BaseVariant $variant): void
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setParent($this);
        }
    }

    public function removeVariant(BaseVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
        }
        return $this;
    }
}