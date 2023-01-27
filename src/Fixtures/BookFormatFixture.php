<?php

declare(strict_types=1);

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formats\BookFormat;

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
