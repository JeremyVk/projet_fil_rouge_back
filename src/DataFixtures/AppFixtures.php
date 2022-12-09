<?php

namespace App\DataFixtures;

use App\Fixtures\BookFixture;
use App\Fixtures\BookFormatFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookFormatFixture::class,
            BookFixture::class,
        ];
    }
}
