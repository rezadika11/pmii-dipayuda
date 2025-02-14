<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    public function datatable()
    {
        $query = Tag::query();

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
        return view('backend.admin.tag.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags,name'
        ], [
            'name.required' => 'Nama tag harus diisi',
            'name.unique' => 'Nama tag sudah ada'
        ]);

        $slug = Str::slug($validatedData['name']);

        try {
            $tag = Tag::create([
                'name' => $validatedData['name'],
                'slug' => $slug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully',
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tag',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json([
            'success' => true,
            'tag' => $tag
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags,name,' . $id,
        ], [
            'name.required' => 'Nama tag harus diisi',
            'name.unique' => 'Nama tag sudah ada'
        ]);

        // Generate slug otomatis
        $slug = Str::slug($validatedData['name']);

        try {
            $tag = Tag::findOrFail($id);
            $tag->update([
                'name' => $validatedData['name'],
                'slug' => $slug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully',
                'tag' => $tag
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tag'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tag deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed deleted tag',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
