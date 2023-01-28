<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Order;
use App\Fixtures\BookFixture;
use App\Repository\BookRepository;
use App\Fixtures\BookFormatFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private BookRepository $bookRepository)
    {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('aaaaa@aaa.fr');
        $user->setPassword('aaaaa');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstname('jeje');
        $user->setLastname('jeje');
        $manager->persist($user);

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
