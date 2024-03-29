<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\Order;
use App\Fixtures\BookAuthorFixture;
use App\Fixtures\BookFixture;
use App\Repository\BookRepository;
use App\Fixtures\BookFormatFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('jeremvk@outlook.fr');
        $user->setPassword($this->passwordHasher->hashPassword($user, "password")); //password
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setFirstname('jeje');
        $user->setLastname('jeje');
        
        $address = new Address();
        $address->setFirstname("Vk");
        $address->setLastname("jérémy");
        $address->setPostalCode("13480");
        $address->setStreet("18 traverse de l'espargoulo");
        $address->setCity('Calas');

        $user->addAddress($address);
        $manager->persist($address);
        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookAuthorFixture::class,
            BookFormatFixture::class,
            BookFixture::class,
        ];
    }
}
