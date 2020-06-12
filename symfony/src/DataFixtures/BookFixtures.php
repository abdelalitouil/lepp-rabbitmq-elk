<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends BaseFixture
{
    private static $bookIndexations = [
        'Pending',
        'Indexed',
    ];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Book::class, 2000, function(Book $book) {
            
            $book->setTitle($this->faker->sentence(random_int(1, 5), true))
                ->setAuthor($this->faker->name('male'|'female'))
                ->setLanguage($this->faker->languageCode)
                ->setSummary($this->faker->paragraph(3, true))
                ->setIndexation($this->faker->randomElement(self::$bookIndexations))
                ->setPublishDate($this->faker->dateTimeBetween('-100 days', '-1 days'))
                ;
        });
        $manager->flush();
    }
}