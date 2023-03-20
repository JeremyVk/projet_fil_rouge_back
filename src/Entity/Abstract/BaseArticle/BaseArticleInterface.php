<?php

declare(strict_types=1);

namespace App\Entity\Abstract\BaseArticle;

use Doctrine\Common\Collections\Collection;
use App\Entity\Abstract\BaseVariant\BaseVariantInterface;

interface BaseArticleInterface
{
    public function getId(): int;

    public function getTitle(): string;
    public function setTitle(string $title): void;

    public function getResume(): string;
    public function setResume(string $resume): void;

    public function getImage(): ?string;
    public function setImage(?string $image): void;

    public function addVariant(BaseVariantInterface $variant): void;
    public function removeVariant(BaseVariantInterface $variant): void;
    public function getVariants(): ?Collection;
}