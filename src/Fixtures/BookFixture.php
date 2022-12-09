<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Book;
use App\Entity\BookVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture
{
    const BOOKS = [
        [
            'title' =>  'Apprendre le HTML',
            'resume' => "Un classique du HTML",
            'isbnNumber' => 15786896,
            'format' => 'brochet',
            'editor' => 'Lys Bleu',
            'author' => 'cyberTech',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/javascript.jpeg',
            'unitPrice' => 4000,
            'stock' => 10,
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                ],
            ]
        ],
        [
            'title' =>   'Apprendre le CSS',
            'resume' => "Faites de beaux sites avec le CSS",
            'isbnNumber' => 15786821,
            'format' => 'Poche',
            'editor' => 'Hachette',
            'author' => 'cssLoveur',
            'gender' => 'Apprentissage Informatique',
            'unitPrice' => 4000,
            'stock' => 10,
            'image' => 'assets/images/css3.jpeg'
        ],
        [
            'title' =>    'Le javascript en 5 étapes',
            'resume' => "Les étapes clef pour apprendre le javascript",
            'isbnNumber' => 1578612,
            'format' => 'Poche',
            'editor' => 'Aparis',
            'author' => 'JsPlayer',
            'gender' => 'Apprentissage Informatique',
            'unitPrice' => 3500,
            'stock' => 10,
            'image' => 'assets/images/javascript.jpeg'
        ],
        [
            'title' =>    'Php de A à Z',
            'resume' => "Le language le plus utilisé pour le web !",
            'isbnNumber' => 1578612,
            'format' => 'Broché',
            'editor' => 'Kartier',
            'author' => 'ElephantMan',
            'gender' => 'Apprentissage Informatique',
            'unitPrice' => 3500,
            'stock' => 10,
            'image' => 'assets/images/php-8.jpeg'
        ],
        [
            'title' =>    'Symfony 6.1',
            'resume' => "Le framewok PHP le plus utilisé en France.",
            'isbnNumber' => 1578612,
            'format' => 'Poche',
            'editor' => 'Php music',
            'author' => 'Mozart',
            'gender' => 'Apprentissage Informatique',
            'unitPrice' => 3500,
            'stock' => 10,
            'image' => 'assets/images/javascript.jpeg'
        ],
        [
            'title' =>    'Apprendre à concevoir une Api avec node js',
            'resume' => "Les étapes clef pour apprendre node js",
            'isbnNumber' => 1578612,
            'format' => 'Poche',
            'editor' => 'Aparis',
            'author' => 'JsPlayer',
            'gender' => 'Apprentissage Informatique',
            'unitPrice' => 3500,
            'stock' => 10,
            'image' => 'assets/images/javascript.jpeg'
        ],
    ];


    public function load(ObjectManager $manager)
    {
        foreach(SELF::BOOKS as $dataBook) {
            $book = new Book();
            $book->setTitle($dataBook['title']);
            $book->setResume($dataBook['resume']);
            $book->setUnitPrice($dataBook['unitPrice']);
            $book->setStock($dataBook['stock']);
            $book->setImage($dataBook['image']);
            $book->setIsbnNumber($dataBook['isbnNumber']);
            $book->setFormat($dataBook['format']);
            $book->setEditor($dataBook['editor']);

            if(isset($dataBook['variants'])) {
                foreach($dataBook['variants'] as $variant) {
                    // dd($variant['stock']);
                    $newVariant = new BookVariant();
                    // dd($variant);
                    $newVariant->setStock($variant['stock']);
                    $newVariant->setUnitPrice($variant['unitPrice']);
                    $book->addBookVariant($newVariant);
                }
            }
            

            $manager->persist($book);

        }
        $manager->flush();
    }


}
