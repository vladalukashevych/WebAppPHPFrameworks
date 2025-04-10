<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class GenreController extends AbstractController
{
    public const ITEMS_PER_PAGE = 10;

    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/genres', name: 'get_genres', methods: [Request::METHOD_GET])]
    public function getGenres(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();
        $page = $queryParams['page'] ?? 1;
        $itemsPerPage = $queryParams['itemsPerPage'] ?? self::ITEMS_PER_PAGE;
        unset($queryParams['page'], $queryParams['itemsPerPage']);

        /** @var array $genres */
        $genres = $this->entityManager->getRepository(Genre::class)->getGenres($queryParams, $page, $itemsPerPage);

        return new JsonResponse(['data' => $genres], Response::HTTP_OK);
    }


    #[Route('/genres/{id}', name: 'get_genre_item', methods: [Request::METHOD_GET])]
    public function getGenreItem(string $id): JsonResponse
    {
        /** @var Genre $genre */
        $genre = $this->entityManager->getRepository(Genre::class)->find($id);
        if (!$genre) {
            return new JsonResponse(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['data' => $genre], Response::HTTP_OK);
    }

    #[Route('/genres', name: 'post_genres', methods: [Request::METHOD_POST])]
    public function createGenre(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $genre = new Genre();
        $genre->setName($requestData['name'])
            ->setDescription($requestData['description'] ?? null);

        $this->entityManager->persist($genre);
        $this->entityManager->flush();

        return new JsonResponse(['data' => $genre], Response::HTTP_CREATED);
    }

    #[Route('/genres/{id}', name: 'patch_genres', methods: [Request::METHOD_PATCH])]
    public function updateGenre(string $id, Request $request): JsonResponse
    {
        /** @var Genre $genre */
        $genre = $this->entityManager->getRepository(Genre::class)->find($id);
        if (!$genre) {
            return new JsonResponse(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['name'])) {
            $genre->setName($requestData['name']);
        }
        if (isset($requestData['description'])) {
            $genre->setDescription($requestData['description']);
        }
        $this->entityManager->flush();

        return new JsonResponse(['data' => $genre], Response::HTTP_OK);
    }

    #[Route('/genres/{id}', name: 'delete_genres', methods: [Request::METHOD_DELETE])]
    public function deleteGenre(string $id): JsonResponse
    {
        /** @var Genre $genre */
        $genre = $this->entityManager->getRepository(Genre::class)->find($id);
        if (!$genre) {
            return new JsonResponse(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($genre);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
