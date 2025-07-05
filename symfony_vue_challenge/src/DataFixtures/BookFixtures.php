<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book1 = new Book();
        $book1->setTitle('El Arte de Programar');
        $book1->setAuthor('Donald Knuth');
        $book1->setPublishedYear(1968);
        $manager->persist($book1);
        $this->addReference('book-knuth', $book1); // Guardamos la referencia para las reseñas

        $book2 = new Book();
        $book2->setTitle('Clean Code');
        $book2->setAuthor('Robert C. Martin');
        $book2->setPublishedYear(2008);
        $manager->persist($book2);
        $this->addReference('book-clean-code', $book2);

        $book3 = new Book();
        $book3->setTitle('Domain-Driven Design');
        $book3->setAuthor('Eric Evans');
        $book3->setPublishedYear(2003);
        $manager->persist($book3);
        $this->addReference('book-ddd', $book3);

        $manager->flush();
    }
}