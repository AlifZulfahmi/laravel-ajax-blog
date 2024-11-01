<?php

namespace App\Http\services\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryService extends Controller
{
    public function dataTable($request)
    {
        if ($request->ajax()) {
            $totalData = Category::count();
            $totalFiltered = $totalData;

            $limit = $request->length;
            $start = $request->start;
            $search = $request->search['value'];

            // Logika pencarian
            if (!empty($search)) {
                $data = Category::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->offset($start)
                    ->get(['id', 'uuid', 'name', 'slug']);

                // Hitung total data yang sesuai dengan hasil pencarian
                $totalFiltered = Category::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->count();
            } else {
                // Jika tidak ada pencarian, ambil semua data
                $data = Category::orderBy('id', 'desc')
                    ->limit($limit)
                    ->offset($start)
                    ->get(['id', 'uuid', 'name', 'slug']);
            }

            return DataTables()->of($data)
                ->addIndexColumn()
                ->setOffset($start)
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                    <div class="d-flex gap-2">
                        <button type="button" class="edit btn btn-success btn-sm" onclick="editData(this)" data-id="' . $data->uuid . '">Edit</button>
                        <button type="button" class="delete btn btn-danger btn-sm" onclick="deleteData(this)" data-id="' . $data->uuid . '">Delete</button>
                    </div>';
                    return $actionBtn;
                })
                ->with([
                    'recordsTotal' => $totalData,
                    'recordsFiltered' => $totalFiltered,
                    'start' => $start
                ])
                ->make(true);
        }
    }


    public function getFirstBy($column, $value)
    {
        return Category::where($column, $value)->first();
    }


    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(array $data, $uuid)
    {
        return Category::where('uuid', $uuid)->update($data);
    }
}