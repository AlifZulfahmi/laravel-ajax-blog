<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;
use App\Http\services\Backend\TagService;


class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        return view('backend.tags.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $data = $request->validated();

        try {

            $this->tagService->create($data);

            return response()->json(['message' => 'Data created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        return response()->json(['data' => $this->tagService->getFirstBy('uuid', $uuid)]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $uuid)
    {
        $data = $request->validated(); // Pastikan data tervalidasi

        $getData = $this->tagService->getFirstBy('uuid', $uuid);

        if (!$getData) {
            return response()->json(['message' => 'Tag not found'], 404);
        }

        try {
            $getData->update($data); // Update data dengan benar
            return response()->json(['message' => 'Data updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {

        $getData = $this->tagService->getFirstBy('uuid', $uuid);

        $getData->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }


    public function serverside(Request $request)
    {
        return $this->tagService->dataTable($request);
    }
}