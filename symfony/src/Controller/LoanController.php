<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class LoanController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    #[Route('/loans', name: 'get_loans', methods: [Request::METHOD_GET])]
    public function getLoans(): JsonResponse
    {
        /** @var Loan[] $loans */
        $loans = $this->entityManager->getRepository(Loan::class)->findAll();
        return new JsonResponse(['data' => $loans], Response::HTTP_OK);
    }

    #[Route('/loans/{id}', name: 'get_loan_item', methods: [Request::METHOD_GET])]
    public function getLoanItem(string $id): JsonResponse
    {
        /** @var Loan $loan */
        $loan = $this->entityManager->getRepository(Loan::class)->find($id);
        if (!$loan) {
            return new JsonResponse(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['data' => $loan], Response::HTTP_OK);
    }

    #[Route('/loans', name: 'post_loans', methods: [Request::METHOD_POST])]
    public function createLoan(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $loan = new Loan();
        
        try {
            $loan->setLoanDate(new \DateTime($requestData['loan_date']));
        } catch (\Exception $e) {
            return new JsonResponse(['data' => ['error' => 'Invalid loan_date format']], Response::HTTP_BAD_REQUEST);
        }
        if (isset($requestData['return_date'])) {
            try {
                $loan->setReturnDate(new \DateTime($requestData['return_date']));
            } catch (\Exception $e) {
                return new JsonResponse(['data' => ['error' => 'Invalid return_date format']], Response::HTTP_BAD_REQUEST);
            }
        }
        $loan->setBorrowerName($requestData['borrower_name']);

        $book = $this->entityManager->getRepository(Book::class)->find($requestData['book_id']);
        if (!$book) {
            return new JsonResponse(['data' => ['error' => 'Not found book by id ' . $requestData['book_id']]], Response::HTTP_NOT_FOUND);
        }
        $loan->setBook($book);

        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        return new JsonResponse(['data' => $loan], Response::HTTP_CREATED);
    }

    #[Route('/loans/{id}', name: 'patch_loans', methods: [Request::METHOD_PATCH])]
    public function updateLoan(string $id, Request $request): JsonResponse
    {
        /** @var Loan $loan */
        $loan = $this->entityManager->getRepository(Loan::class)->find($id);
        if (!$loan) {
            return new JsonResponse(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);
        if (isset($requestData['loan_date'])) {
            try {
                $loan->setLoanDate(new \DateTime($requestData['loan_date']));
            } catch (\Exception $e) {
                return new JsonResponse(['data' => ['error' => 'Invalid loan_date format']], Response::HTTP_BAD_REQUEST);
            }
        }
        if (isset($requestData['return_date'])) {
            try {
                $loan->setReturnDate(new \DateTime($requestData['return_date']));
            } catch (\Exception $e) {
                return new JsonResponse(['data' => ['error' => 'Invalid return_date format']], Response::HTTP_BAD_REQUEST);
            }
        }
        if (isset($requestData['borrower_name'])) {
            $loan->setBorrowerName($requestData['borrower_name']);
        }
        if (isset($requestData['book_id'])) {
            $book = $this->entityManager->getRepository(Book::class)->find($requestData['book_id']);
            if (!$book) {
                return new JsonResponse(['data' => ['error' => 'Not found book by id ' . $requestData['book_id']]], Response::HTTP_NOT_FOUND);
            }
            $loan->setBook($book);
        }
        $this->entityManager->flush();

        return new JsonResponse(['data' => $loan], Response::HTTP_OK);
    }

    #[Route('/loans/{id}', name: 'delete_loans', methods: [Request::METHOD_DELETE])]
    public function deleteLoan(string $id): JsonResponse
    {
        /** @var Loan $loan */
        $loan = $this->entityManager->getRepository(Loan::class)->find($id);
        if (!$loan) {
            return new JsonResponse(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($loan);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
