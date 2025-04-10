<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class AuthorController extends AbstractController
{
    public const ITEMS_PER_PAGE = 10;

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/authors', name: 'get_authors', methods: [Request::METHOD_GET])]
    public function getAuthors(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();
        $page = $queryParams['page'] ?? 1;
        $itemsPerPage = $queryParams['itemsPerPage'] ?? self::ITEMS_PER_PAGE;
        unset($queryParams['page'], $queryParams['itemsPerPage']);

        /** @var array $authors */
        $authors = $this->entityManager->getRepository(Author::class)->getAuthors($queryParams, $page, $itemsPerPage);

        return new JsonResponse(['data' => $authors], Response::HTTP_OK);
    }

    #[Route('/authors/{id}', name: 'get_author_item', methods: [Request::METHOD_GET])]
    public function getAuthorItem(string $id): JsonResponse
    {
        /** @var Author $author */
        $author = $this->entityManager->getRepository(Author::class)->find($id);
        if (!$author) {
            return new JsonResponse(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['data' => $author], Response::HTTP_OK);
    }

    #[Route('/authors', name: 'post_authors', methods: [Request::METHOD_POST])]
    public function createAuthor(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $author = new Author();
        $author->setName($requestData['name'])
            ->setBio($requestData['bio'] ?? null);

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return new JsonResponse(['data' => $author], Response::HTTP_CREATED);
    }

    #[Route('/authors/{id}', name: 'patch_authors', methods: [Request::METHOD_PATCH])]
    public function updateAuthor(string $id, Request $request): JsonResponse
    {
        /** @var Author $author */
        $author = $this->entityManager->getRepository(Author::class)->find($id);
        if (!$author) {
            return new JsonResponse(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['name'])) {
            $author->setName($requestData['name']);
        }
        if (isset($requestData['bio'])) {
            $author->setBio($requestData['bio']);
        }
        $this->entityManager->flush();

        return new JsonResponse(['data' => $author], Response::HTTP_OK);
    }

    #[Route('/authors/{id}', name: 'delete_authors', methods: [Request::METHOD_DELETE])]
    public function deleteAuthor(string $id): JsonResponse
    {
        /** @var Author $author */
        $author = $this->entityManager->getRepository(Author::class)->find($id);
        if (!$author) {
            return new JsonResponse(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($author);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
