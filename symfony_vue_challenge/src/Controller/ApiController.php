<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    ) {}

    #[Route('/books', name: 'books', methods: ['GET'])]
    public function getBooks(): JsonResponse
    {
        $booksData = $this->bookRepository->findAllWithAverageRating();

        return $this->json($booksData);
    }

    #[Route('/reviews', name: 'add_review', methods: ['POST'])]
    public function addReview(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (null === $data) {
            return $this->json(['message' => 'Invalid JSON body.'], Response::HTTP_BAD_REQUEST);
        }

        $bookId = $data['book_id'] ?? null;
        $rating = $data['rating'] ?? null;
        $comment = $data['comment'] ?? null;

        // 1. Validar que el book_id exista
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            return $this->json(['message' => 'Book not found.'], Response::HTTP_NOT_FOUND);
        }

        $review = new Review();
        $review->setBook($book);
        $review->setRating($rating);
        $review->setComment($comment);

        // 2. Validar con el componente Validator
        $errors = $this->validator->validate($review);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Guardar la reseña
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Review added successfully!',
            'review_id' => $review->getId()
        ], Response::HTTP_CREATED);
    }

    // ... Otros endpoints aquí ...
}