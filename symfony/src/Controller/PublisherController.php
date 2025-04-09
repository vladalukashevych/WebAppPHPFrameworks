<?php

namespace App\Controller;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class PublisherController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/publishers', name: 'get_publishers', methods: [Request::METHOD_GET])]
    public function getPublishers(): JsonResponse
    {
        /** @var Publisher[] $publishers */
        $publishers = $this->entityManager->getRepository(Publisher::class)->findAll();
        return new JsonResponse(['data' => $publishers], Response::HTTP_OK);
    }

    #[Route('/publishers/{id}', name: 'get_publisher_item', methods: [Request::METHOD_GET])]
    public function getPublisherItem(string $id): JsonResponse
    {
        /** @var Publisher $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);
        if (!$publisher) {
            return new JsonResponse(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['data' => $publisher], Response::HTTP_OK);
    }

    #[Route('/publishers', name: 'post_publishers', methods: [Request::METHOD_POST])]
    public function createPublisher(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $publisher = new Publisher();
        $publisher->setName($requestData['name'])
            ->setAddress($requestData['address'] ?? null);

        $this->entityManager->persist($publisher);
        $this->entityManager->flush();

        return new JsonResponse(['data' => $publisher], Response::HTTP_CREATED);
    }

    #[Route('/publishers/{id}', name: 'patch_publishers', methods: [Request::METHOD_PATCH])]
    public function updatePublisher(string $id, Request $request): JsonResponse
    {
        /** @var Publisher $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);
        if (!$publisher) {
            return new JsonResponse(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['name'])) {
            $publisher->setName($requestData['name']);
        }
        if (isset($requestData['address'])) {
            $publisher->setAddress($requestData['address']);
        }
        $this->entityManager->flush();

        return new JsonResponse(['data' => $publisher], Response::HTTP_OK);
    }

    #[Route('/publishers/{id}', name: 'delete_publishers', methods: [Request::METHOD_DELETE])]
    public function deletePublisher(string $id): JsonResponse
    {
        /** @var Publisher $publisher */
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);
        if (!$publisher) {
            return new JsonResponse(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($publisher);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
