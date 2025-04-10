<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Repository\LoanRepository;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class LoanController extends Controller
{
    public const ITEMS_PER_PAGE = 10;

    private LoanRepository $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    /**
     * Get all loans.
     *
     * @param Request $request
     * @return mixed
     */
    public function getLoans(Request $request): mixed
    {
        $queryParams = $request->all();
        $itemsPerPage = $queryParams['itemsPerPage'] ?? self::ITEMS_PER_PAGE;
        unset($queryParams['page'], $queryParams['itemsPerPage']);
        $loans = $this->loanRepository->getLoans($queryParams, $itemsPerPage);
        return response()->json($loans, Response::HTTP_OK);
    }

    /**
     * Get a single loan by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function getLoanItem(string $id): mixed
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['data' => $loan], Response::HTTP_OK);
    }

    /**
     * Create a new loan.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createLoan(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);

        $book = Book::find($requestData['book_id']);
        if (!$book) {
            return response()->json(['data' => ['error' => 'Not found book by id ' . $requestData['book_id']]], Response::HTTP_NOT_FOUND);
        }

        $loan = $book->loans()->create([
            'borrower_name' => $requestData['borrower_name'],
            'loan_date'     => $requestData['loan_date'],
            'return_date'   => $requestData['return_date'] ?? null,
        ]);

        return response()->json(['data' => $loan], Response::HTTP_CREATED);
    }

    /**
     * Update a loan by ID.
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateLoan(string $id, Request $request): mixed
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['borrower_name'])) {
            $loan->borrower_name = $requestData['borrower_name'];
        }
        if (isset($requestData['loan_date'])) {
            $loan->loan_date = $requestData['loan_date'];
        }
        if (isset($requestData['return_date'])) {
            $loan->return_date = $requestData['return_date'];
        }
        if (isset($requestData['book_id'])) {
            $book = Book::find($requestData['book_id']);
            if (!$book) {
                return response()->json(['data' => ['error' => 'Not found book by id ' . $requestData['book_id']]], Response::HTTP_NOT_FOUND);
            }
            $loan->book()->associate($book);
        }

        $loan->save();
        return response()->json(['data' => $loan], Response::HTTP_OK);
    }

    /**
     * Delete a loan by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function deleteLoan(string $id): mixed
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['data' => ['error' => 'Not found loan by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $loan->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
