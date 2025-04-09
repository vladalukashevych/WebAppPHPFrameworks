<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class BookController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/books', name: 'get_books', methods: [Request::METHOD_GET])]
    public function getBooks(): JsonResponse
    {
        /** @var Book[] $books */
        $books = $this->entityManager->getRepository(Book::class)->findAll();
        return new JsonResponse(['data' => $books], Response::HTTP_OK);
    }

    #[Route('/books/{id}', name: 'get_book_item', methods: [Request::METHOD_GET])]
    public function getBookItem(string $id): JsonResponse
    {
        /** @var Book $book */
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            return new JsonResponse(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['data' => $book], Response::HTTP_OK);
    }

    #[Route('/books', name: 'post_books', methods: [Request::METHOD_POST])]
    public function createBook(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $book = new Book();
        $book->setTitle($requestData['title'])
            ->setPublicationYear($requestData['publication_year']);

        if (isset($requestData['author_id'])) {
            $author = $this->entityManager->getRepository(Author::class)->find($requestData['author_id']);
            if (!$author) {
                return new JsonResponse(['data' => ['error' => 'Not found author by id ' . $requestData['author_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setAuthor($author);
        }
        if (isset($requestData['genre_id'])) {
            $genre = $this->entityManager->getRepository(Genre::class)->find($requestData['genre_id']);
            if (!$genre) {
                return new JsonResponse(['data' => ['error' => 'Not found genre by id ' . $requestData['genre_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setGenre($genre);
        }
        if (isset($requestData['publisher_id'])) {
            $publisher = $this->entityManager->getRepository(Publisher::class)->find($requestData['publisher_id']);
            if (!$publisher) {
                return new JsonResponse(['data' => ['error' => 'Not found publisher by id ' . $requestData['publisher_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setPublisher($publisher);
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new JsonResponse(['data' => $book], Response::HTTP_CREATED);
    }

    #[Route('/books/{id}', name: 'patch_books', methods: [Request::METHOD_PATCH])]
    public function updateBook(string $id, Request $request): JsonResponse
    {
        /** @var Book $book */
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            return new JsonResponse(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['title'])) {
            $book->setTitle($requestData['title']);
        }
        if (isset($requestData['publication_year'])) {
            $book->setPublicationYear($requestData['publication_year']);
        }
        if (isset($requestData['author_id'])) {
            $author = $this->entityManager->getRepository(Author::class)->find($requestData['author_id']);
            if (!$author) {
                return new JsonResponse(['data' => ['error' => 'Not found author by id ' . $requestData['author_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setAuthor($author);
        }
        if (isset($requestData['genre_id'])) {
            $genre = $this->entityManager->getRepository(Genre::class)->find($requestData['genre_id']);
            if (!$genre) {
                return new JsonResponse(['data' => ['error' => 'Not found genre by id ' . $requestData['genre_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setGenre($genre);
        }
        if (isset($requestData['publisher_id'])) {
            $publisher = $this->entityManager->getRepository(Publisher::class)->find($requestData['publisher_id']);
            if (!$publisher) {
                return new JsonResponse(['data' => ['error' => 'Not found publisher by id ' . $requestData['publisher_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->setPublisher($publisher);
        }
        $this->entityManager->flush();

        return new JsonResponse(['data' => $book], Response::HTTP_OK);
    }

    #[Route('/books/{id}', name: 'delete_books', methods: [Request::METHOD_DELETE])]
    public function deleteBook(string $id): JsonResponse
    {
        /** @var Book $book */
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        if (!$book) {
            return new JsonResponse(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
