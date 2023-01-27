<?php

namespace App\Entity\Formats\Interfaces;

use App\Entity\Abstract\BaseVariant\BaseVariantInterface;

interface FormatInterface
{
    public function getId(): int;

    public function getName(): string;
    public function setName(string $name): void;
}