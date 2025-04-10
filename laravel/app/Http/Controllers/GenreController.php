<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Repository\GenreRepository;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class GenreController extends Controller
{
    public const ITEMS_PER_PAGE = 10;

    private GenreRepository $genreRepository;

    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * Get all genres.
     *
     * @param Request $request
     * @return mixed
     */
    public function getGenres(Request $request): mixed
    {
        $queryParams = $request->all();
        $itemsPerPage = $queryParams['itemsPerPage'] ?? self::ITEMS_PER_PAGE;
        unset($queryParams['page'], $queryParams['itemsPerPage']);
        $genres = $this->genreRepository->getGenres($queryParams, $itemsPerPage);
        return response()->json($genres, Response::HTTP_OK);
    }

    /**
     * Get a single genre by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function getGenreItem(string $id): mixed
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['data' => $genre], Response::HTTP_OK);
    }

    /**
     * Create a new genre.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createGenre(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);
        $genre = Genre::create([
            'name' => $requestData['name'],
            'description' => $requestData['description'] ?? null,
        ]);
        return response()->json(['data' => $genre], Response::HTTP_CREATED);
    }

    /**
     * Update a genre by ID.
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateGenre(string $id, Request $request): mixed
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $requestData = json_decode($request->getContent(), true);
        $genre->update([
            'name' => $requestData['name'] ?? $genre->name,
            'description' => $requestData['description'] ?? $genre->description,
        ]);
        return response()->json(['data' => $genre], Response::HTTP_OK);
    }

    /**
     * Delete a genre by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function deleteGenre(string $id): mixed
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['data' => ['error' => 'Not found genre by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $genre->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
