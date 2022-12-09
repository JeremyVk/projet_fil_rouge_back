<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Book;
use App\Entity\BookVariant;
use Doctrine\Persistence\ObjectManager;
use App\Repository\BookFormatRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BookFixture extends Fixture
{
    const BOOKS = [
        [
            'title' =>  'Apprendre le HTML',
            'resume' => "Un classique du HTML",
            'editor' => 'Lys Bleu',
            'author' => 'cyberTech',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/javascript.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
            ]
        ],
        [
            'title' =>   'Apprendre le CSS',
            'resume' => "Faites de beaux sites avec le CSS",
            'editor' => 'Hachette',
            'author' => 'cssLoveur',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/css3.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                    'isbnNumber' => 157868969,
                    'format' => 2,
                ],
            ]
        ],
        [
            'title' =>    'Le javascript en 5 étapes',
            'resume' => "Les étapes clef pour apprendre le javascript",
            'editor' => 'Aparis',
            'author' => 'JsPlayer',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/javascript.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                    'isbnNumber' => 157868969,
                    'format' => 2,
                ],
            ]
        ],
        [
            'title' =>    'Php de A à Z',
            'resume' => "Le language le plus utilisé pour le web !",
            'editor' => 'Kartier',
            'author' => 'ElephantMan',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/php-8.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                    'isbnNumber' => 157868969,
                    'format' => 2,
                ],
            ]
        ],
        [
            'title' =>    'Symfony 6.1',
            'resume' => "Le framewok PHP le plus utilisé en France.",
            'editor' => 'Php music',
            'author' => 'Mozart',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/javascript.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 4000,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                    'isbnNumber' => 157868969,
                    'format' => 2,
                ],
            ]
        ],
        [
            'title' =>    'Apprendre à concevoir une Api avec node js',
            'resume' => "Les étapes clef pour apprendre node js",
            'editor' => 'Aparis',
            'author' => 'JsPlayer',
            'gender' => 'Apprentissage Informatique',
            'image' => 'assets/images/javascript.jpeg',
            'variants' => [
                [
                    'stock' => 10,
                    'unitPrice' => 1500,
                    'isbnNumber' => 15786896,
                    'format' => 1,
                ],
                [
                    'stock' => 30,
                    'unitPrice' => 550,
                    'isbnNumber' => 157868969,
                    'format' => 2,
                ],
            ]
        ],
    ];

    public function __construct(private BookFormatRepository $bookFormatRepository)
    {
    }


    public function load(ObjectManager $manager)
    {
        foreach(SELF::BOOKS as $dataBook) {
            $book = new Book();
            $book->setTitle($dataBook['title']);
            $book->setResume($dataBook['resume']);
            $book->setImage($dataBook['image']);
            $book->setEditor($dataBook['editor']);

            if(isset($dataBook['variants'])) {
                foreach($dataBook['variants'] as $variant) {
                    $newVariant = new BookVariant();
                    $newVariant->setStock($variant['stock']);
                    $newVariant->setUnitPrice($variant['unitPrice']);
                    $newVariant->setIsbnNumber($variant['isbnNumber']);
                    $format = $this->bookFormatRepository->find($variant['format']);
                    $newVariant->setFormat($format);
                    $book->addVariant($newVariant);
                }
            }
            

            $manager->persist($book);

        }
        $manager->flush();
    }


}
