<?php

namespace App\Fixtures;

use App\Entity\Author\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class BookAuthorFixture extends Fixture
{
    const AUTHORS = [
        [
            "firstname" => "Fabien",
            "lastname" => "Potencier",
            "language" => "Français"
        ],
        [
            "firstname" => "Olivier",
            "lastname" => "Heurthel",
            "language" => "Français"
        ],
        [
            "firstname" => "Edd",
            "lastname" => "Tittel",
            "language" => "Français"
        ],
        [
            "firstname" => "Emily",
            "lastname" => "VanderVeer",
            "language" => "Français"
        ],
        [
            "firstname" => "Robin",
            "lastname" => "Wieruch",
            "language" => "Français"
        ],
        [
            "firstname" => "Jean-Pierre",
            "lastname" => "Vincent",
            "language" => "Français"
        ],
        [
            "firstname" => "Jonathan",
            "lastname" => "Verrecchia",
            "language" => "Français"
        ],
        [
            "firstname" => "Thomas",
            "lastname" => "Parisot",
            "language" => "Français"
        ],
    ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        foreach(self::AUTHORS as $author) {
            $newAuthor = new Author();
            $newAuthor->setFirstname($author['firstname']);
            $newAuthor->setLastname($author['lastname']);
            $newAuthor->setLanguage($author['language']);

            $manager->persist($newAuthor);
        }

        $manager->flush();
    }
}