<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class PublisherController extends Controller
{
    /**
     * Get all publishers.
     *
     * @return mixed
     */
    public function getPublishers(): mixed
    {
        $publishers = Publisher::all();
        return response()->json($publishers, Response::HTTP_OK);
    }

    /**
     * Get a single publisher by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function getPublisherItem(string $id): mixed
    {
        $publisher = Publisher::find($id);
        if (!$publisher) {
            return response()->json(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['data' => $publisher], Response::HTTP_OK);
    }

    /**
     * Create a new publisher.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createPublisher(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);
        $publisher = Publisher::create([
            'name' => $requestData['name'],
            'address' => $requestData['address'] ?? null,
        ]);
        return response()->json(['data' => $publisher], Response::HTTP_CREATED);
    }

    /**
     * Update a publisher by ID.
     *
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updatePublisher(string $id, Request $request): mixed
    {
        $publisher = Publisher::find($id);
        if (!$publisher) {
            return response()->json(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $requestData = json_decode($request->getContent(), true);
        $publisher->update([
            'name' => $requestData['name'] ?? $publisher->name,
            'address' => $requestData['address'] ?? $publisher->address,
        ]);
        return response()->json(['data' => $publisher], Response::HTTP_OK);
    }

    /**
     * Delete a publisher by ID.
     *
     * @param string $id
     * @return mixed
     */
    public function deletePublisher(string $id): mixed
    {
        $publisher = Publisher::find($id);
        if (!$publisher) {
            return response()->json(['data' => ['error' => 'Not found publisher by id ' . $id]], Response::HTTP_NOT_FOUND);
        }
        $publisher->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
