<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function datatable()
    {
        $query = Category::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<button type="button" class="btn btn-sm btn-success btn-edit" data-id="' . $row->id . '">
            <i class="bi bi-pencil-square"></i> Edit
        </button>';

                $btnDelete = '<button type="button" class="btn btn-sm btn-danger btn-delete" 
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i> Hapus
                    </button>';

                return $btnEdit . ' ' . $btnDelete;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function index()
    {
        return view('backend.admin.category.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name'
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        $slug = Str::slug($validatedData['name']);

        try {
            $category = Category::create([
                'name' => $validatedData['name'],
                'slug' => $slug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil disimpan!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori gagal disimpan!',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        // Generate slug otomatis
        $slug = Str::slug($validatedData['name']);

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $validatedData['name'],
                'slug' => $slug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed deleted category',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
