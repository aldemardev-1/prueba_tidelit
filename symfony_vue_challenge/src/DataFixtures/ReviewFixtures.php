<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $review1 = new Review();
        $review1->setBook($this->getReference('book-knuth', Book::class));
        $review1->setRating(5);
        $review1->setComment('Un clásico atemporal. Fundamental para cualquier programador serio.');
        $manager->persist($review1);

        $review2 = new Review();
        $review2->setBook($this->getReference('book-knuth', Book::class));
        $review2->setRating(4);
        $review2->setComment('Excelente, aunque un poco denso para principiantes.');
        $manager->persist($review2);

        $review3 = new Review();
        $review3->setBook($this->getReference('book-clean-code', Book::class));
        $review3->setRating(5);
        $review3->setComment('Debería ser lectura obligatoria para todos los desarrolladores.');
        $manager->persist($review3);

        $review4 = new Review();
        $review4->setBook($this->getReference('book-clean-code', Book::class));
        $review4->setRating(4);
        $review4->setComment('Buenos principios, aunque algunos ejemplos son un poco anticuados.');
        $manager->persist($review4);


        $review5 = new Review();
        $review5->setBook($this->getReference('book-ddd', Book::class));
        $review5->setRating(5);
        $review5->setComment('Transformador. Cambió mi forma de pensar sobre el diseño de software.');
        $manager->persist($review5);

        $review6 = new Review();
        $review6->setBook($this->getReference('book-ddd', Book::class));
        $review6->setRating(4);
        $review6->setComment('Requiere relecturas, pero es invaluable para proyectos complejos.');
        $manager->persist($review6);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookFixtures::class,
        ];
    }
}