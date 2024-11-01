<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Imports\CategoryImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CategoryRequest;
use App\Http\services\Backend\CategoryService;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function __construct(private CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('backend.categories.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        try {

            $this->categoryService->create($data);

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
        return response()->json(['data' => $this->categoryService->getFirstBy('uuid', $uuid)]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $uuid)
    {
        $data = $request->validated(); // Pastikan data tervalidasi

        $getData = $this->categoryService->getFirstBy('uuid', $uuid);

        if (!$getData) {
            return response()->json(['message' => 'Category not found'], 404);
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

        $getData = $this->categoryService->getFirstBy('uuid', $uuid);

        $getData->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file_import' => 'required|mimes:csv,xls,xlsx'
            ]);

            // import class
            Excel::import(new CategoryImport, $request->file('file_import'));

            return redirect()->back()->with('success', 'Import Data Kategori Berhasil!');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function serverside(Request $request)
    {
        return $this->categoryService->dataTable($request);
    }
}