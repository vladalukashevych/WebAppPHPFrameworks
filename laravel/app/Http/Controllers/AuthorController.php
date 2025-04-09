<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class AuthorController extends Controller
{
    /**
     * Get all authors.
     *
     * @return mixed
     */
    public function getAuthors(): mixed
    {
        $authors = Author::all();
        return response()->json($authors, Response::HTTP_OK);
    }

    /**
     * Get a single author by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function getAuthorItem(string $id): mixed
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['data' => $author], Response::HTTP_OK);
    }

    /**
     * Create a new author.*
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createAuthor(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);
        $author = Author::create([
            'name' => $requestData['name'],
            'bio' => $requestData['bio'] ?? null,
        ]);
        return response()->json(['data' => $author], Response::HTTP_CREATED);
    }

    /**
     * Update an author by ID.
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateAuthor(string $id, Request $request): mixed
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $requestData = json_decode($request->getContent(), true);
        $author->update([
            'name' => $requestData['name'] ?? $author->name,
            'bio' => $requestData['bio'] ?? $author->bio,
        ]);
        return response()->json(['data' => $author], Response::HTTP_OK);
    }

    /**
     * Delete an author by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function deleteAuthor(string $id): mixed
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['data' => ['error' => 'Not found author by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $author->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
