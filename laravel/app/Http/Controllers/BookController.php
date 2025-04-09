<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class BookController extends Controller
{
    /**
     * Get all books.
     *
     * @return mixed
     */
    public function getBooks(): mixed
    {
        $books = Book::all();
        return response()->json($books, Response::HTTP_OK);
    }

    /**
     * Get a single book by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function getBookItem(string $id): mixed
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['data' => $book], Response::HTTP_OK);
    }

    /**
     * Create a new book.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createBook(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);

        $author = Author::find($requestData['author_id']);
        if (!$author) {
            return response()->json(['data' => ['error' => 'Not found author by id ' . $requestData['author_id']]], Response::HTTP_NOT_FOUND);
        }

        $genre = Genre::find($requestData['genre_id']);
        if (!$genre) {
            return response()->json(['data' => ['error' => 'Not found genre by id ' . $requestData['genre_id']]], Response::HTTP_NOT_FOUND);
        }

        $publisher = null;
        if (isset($requestData['publisher_id'])) {
            $publisher = Publisher::find($requestData['publisher_id']);
            if (!$publisher) {
                return response()->json(['data' => ['error' => 'Not found publisher by id ' . $requestData['publisher_id']]], Response::HTTP_NOT_FOUND);
            }
        }

        $book = $author->books()->create([
            'title'             => $requestData['title'],
            'publication_year'  => $requestData['publication_year'],
            'genre_id_id'          => $genre->id,
            'publisher_id_id'      => $publisher ? $publisher->id : null,
        ]);

        return response()->json(['data' => $book], Response::HTTP_CREATED);
    }

    /**
     * Update a book by ID.
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateBook(string $id, Request $request): mixed
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['title'])) {
            $book->title = $requestData['title'];
        }
        if (isset($requestData['publication_year'])) {
            $book->publication_year = $requestData['publication_year'];
        }
        if (isset($requestData['author_id'])) {
            $author = Author::find($requestData['author_id']);
            if (!$author) {
                return response()->json(['data' => ['error' => 'Not found author by id ' . $requestData['author_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->author()->associate($author);
        }
        if (isset($requestData['genre_id'])) {
            $genre = Genre::find($requestData['genre_id']);
            if (!$genre) {
                return response()->json(['data' => ['error' => 'Not found genre by id ' . $requestData['genre_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->genre()->associate($genre);
        }
        if (isset($requestData['publisher_id'])) {
            $publisher = Publisher::find($requestData['publisher_id']);
            if (!$publisher) {
                return response()->json(['data' => ['error' => 'Not found publisher by id ' . $requestData['publisher_id']]], Response::HTTP_NOT_FOUND);
            }
            $book->publisher()->associate($publisher);
        }

        $book->save();
        return response()->json(['data' => $book], Response::HTTP_OK);
    }

    /**
     * Delete a book by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function deleteBook(string $id): mixed
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['data' => ['error' => 'Not found book by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $book->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
