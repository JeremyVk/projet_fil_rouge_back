<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Book;
use App\Entity\BookFormat;
use App\Entity\BookVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFormatFixture extends Fixture
{
    public const bookFormats = [
        "Broché",
        "Poche",
        'Standard',
        'Grand Format',
        'Numérique'
    ];
   
    public function load(ObjectManager $manager)
    {
        foreach(self::bookFormats as $bookFormat) {
            $newFormat = new BookFormat();
            $newFormat->setName($bookFormat);
            $manager->persist($newFormat);
        }
            $manager->flush();
    }
}
