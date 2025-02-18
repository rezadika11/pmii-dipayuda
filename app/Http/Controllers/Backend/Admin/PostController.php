<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{

    public function datatable()
    {
        // Pastikan untuk eager load relasi agar tidak terjadi N+1 problem
        $query = Post::with(['users', 'category']);

        return DataTables::of($query)
            ->addIndexColumn() // Kolom No
            ->addColumn('judul', function ($row) {
                return $row->title;
            })
            ->addColumn('penulis', function ($row) {
                // Pastikan relasi author ada, jika tidak tampilkan tanda -
                return $row->users ? $row->users->name : '-';
            })
            ->addColumn('kategori', function ($row) {
                return $row->category ? $row->category->name : '-';
            })
            ->editColumn('tanggal', function ($row) {
                // Format tanggal sesuai kebutuhan, misal menggunakan created_at
                return Carbon::parse($row->published)->format('d-m-Y');
            })
            ->addColumn('publish', function ($row) {
                $status    = $row->is_published; // asumsikan 1 = published, 0 = draft
                $badgeClass = $status ? 'bg-info' : 'bg-warning';
                $label      = $status ? 'Published' : 'Draft';

                // Tambahkan class "btn-toggle-publish" dan data-id serta data-status
                return '<span class="badge ' . $badgeClass . ' btn-toggle-publish" 
                data-id="' . $row->id . '" 
                data-status="' . $status . '" 
                style="cursor:pointer;">' . $label . '</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('posts.edit', $row->id) . '" class="btn btn-sm btn-success btn-edit" data-id="' . $row->id . '">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>';
                $btnDelete = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i> Hapus
                        </button>';
                return $btnEdit . ' ' . $btnDelete;
            })
            // Pastikan kolom yang berisi HTML di-render dengan benar
            ->rawColumns(['publish', 'aksi'])
            ->make(true);
    }

    public function togglePublish(Request $request)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'id' => 'required|exists:posts,id',
            'is_published' => 'required|in:0,1'
        ]);

        $post = Post::findOrFail($request->id);
        $post->is_published = $request->is_published;
        $post->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }


    public function index()
    {
        return view('backend.admin.post.index');
    }

    public function create()
    {
        $data['categories'] = Category::all();
        $data['tags'] = Tag::all();
        $data['users'] = User::all();
        return view('backend.admin.post.create', $data);
    }

    public function upload(Request $request)
    {
        try {
            $messages = [
                'file.required' => 'Harap unggah file gambar.',
                'file.image' => 'File yang diunggah harus berupa gambar.',
                'file.max' => 'Ukuran gambar maksimal 2MB.',
                'file.mimes' => 'Format gambar yang diperbolehkan hanya png, jpg, atau jpeg.',
            ];

            // Validate the upload
            $validated = $request->validate([
                'file' => [
                    'required',
                    'image',
                    'max:2048', // 2MB Max
                    'mimes:jpeg,png,jpg',
                ]
            ], $messages);

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Generate safe filename
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Store file
                $path = $file->storeAs('uploads', $filename, 'public');

                if (!$path) {
                    throw new \Exception('Gagal menyimpan file.');
                }

                return response()->json(asset('storage/' . $path), 200);
            }

            return response()->json(['message' => 'File tidak ditemukan.'], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {

        // Validasi input
        $validated = $request->validate([
            'title'             => 'required|max:255|unique:posts,title',
            'content'           => 'required',
            'excerpt'           => 'required',
            'meta_description'  => 'required',
            'category'          => 'required',
            'tag'               => 'required', // pastikan ini mengirimkan array, misalnya dari checkbox
            'author'            => 'required',
            'published'         => 'required',
            'is_published'      => 'required',
            'image'             => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ], [
            'title.required'            => 'Judul post harus diisi.',
            'title.unique'              => 'Judul post sudah ada.',
            'title.max'                 => 'Judul post maksimal 255 karakter.',
            'content.required'          => 'Isi post harus diisi.',
            'excerpt.required'          => 'Deskripsi post harus diisi.',
            'meta_description.required' => 'SEO Meta Deskripsi post harus diisi.',
            'category.required'         => 'Kategori post harus dipilih.',
            'tag.required'              => 'Tag post harus dipilih.',
            'author.required'           => 'Author post harus dipilih.',
            'published.required'        => 'Tanggal publikasi post harus dipilih.',
            'is_published.required'     => 'Status publikasi post harus dipilih.',
            'image.required'            => 'Gambar post harus diunggah.',
            'image.image'               => 'File yang diunggah harus berupa gambar.',
            'image.mimes'               => 'Format gambar yang diperbolehkan hanya png, jpg, atau jpeg.',
            'image.max'                 => 'Ukuran gambar maksimal 1MB.',
        ]);

        try {
            // Proses upload gambar
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Generate safe filename
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Simpan file ke direktori 'thumbnail' pada disk 'public'
                $path = $file->storeAs('thumbnail', $filename, 'public');

                if (!$path) {
                    throw new \Exception('Gagal menyimpan file.');
                }
            } else {
                throw new \Exception('Gambar post harus diunggah.');
            }

            // Simpan data post ke database
            $post = Post::create([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title),
                'content'           => $request->content,
                'excerpt'           => $request->excerpt,
                'meta_description'  => $request->meta_description,
                'category_id'       => $request->category, // Asumsi: field category mengirimkan ID kategori
                'user_id'           => $request->author,   // Asumsi: field author mengirimkan ID author
                'published'         => $request->published,
                'is_published'      => $request->is_published,
                'image'             => $path,
            ]);

            // Menggunakan attach untuk menambahkan relasi ke tag
            // Asumsi: $request->tag adalah array berisi ID tag, misalnya [1, 2, 3]
            $post->tags()->sync($request->tag);
            toastr()->success('Pos berhasil disimpan');
            return redirect()->route('posts.index');
        } catch (\Exception $e) {
            // Redirect kembali dengan input yang telah diisi dan menampilkan pesan error
            toastr()->error('Terjadi kesalahan');
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $post = Post::with('tags')->findOrFail($id);
        $data['categories'] = Category::all();
        $data['tags'] = Tag::all();
        $data['users'] = User::all();
        $data['post'] = $post;

        return view('backend.admin.post.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'title'             => 'required|max:255|unique:posts,title,' . $id,
            'content'           => 'required',
            'excerpt'           => 'required',
            'meta_description'  => 'required',
            'category'          => 'required|exists:categories,id',
            'tag'               => 'nullable|array',
            'tag.*'             => 'exists:tags,id',
            'author'            => 'required',
            'published'         => 'required|date',
            'is_published'      => 'required|boolean',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ], [
            'title.required'            => 'Judul post harus diisi.',
            'title.unique'              => 'Judul post sudah ada.',
            'title.max'                 => 'Judul post maksimal 255 karakter.',
            'content.required'          => 'Isi post harus diisi.',
            'excerpt.required'          => 'Deskripsi post harus diisi.',
            'meta_description.required' => 'SEO Meta Deskripsi post harus diisi.',
            'category.required'         => 'Kategori post harus dipilih.',
            'tag.*.exists'              => 'Tag yang dipilih tidak valid.',
            'author.required'           => 'Author post harus dipilih.',
            'published.required'        => 'Tanggal publikasi post harus dipilih.',
            'is_published.required'     => 'Status publikasi post harus dipilih.',
            'image.image'               => 'File yang diunggah harus berupa gambar.',
            'image.mimes'               => 'Format gambar yang diperbolehkan hanya png, jpg, atau jpeg.',
            'image.max'                 => 'Ukuran gambar maksimal 1MB.',
        ]);

        try {
            // Ambil post yang akan diperbarui
            $post = Post::findOrFail($id);

            // Proses upload gambar jika ada file baru
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Generate safe filename
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Simpan file ke direktori 'thumbnail' pada disk 'public'
                $path = $file->storeAs('thumbnail', $filename, 'public');

                if (!$path) {
                    throw new \Exception('Gagal menyimpan file gambar.');
                }

                // Hapus gambar lama jika ada
                if ($post->image) {
                    $oldImagePath = storage_path('app/public/' . $post->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                // Update kolom gambar
                $post->image = $path;
            }

            // Update data post
            $post->update([
                'title'             => $request->title,
                'slug'              => Str::slug($request->title),
                'content'           => $request->content,
                'excerpt'           => $request->excerpt,
                'meta_description'  => $request->meta_description,
                'category_id'       => $request->category,
                'user_id'           => $request->author,
                'published'         => $request->published,
                'is_published'      => $request->is_published,
            ]);

            // Update relasi tags
            if ($request->has('tag')) {
                $post->tags()->sync($request->tag);
            } else {
                $post->tags()->sync([]); // Kosongkan relasi jika tidak ada tag
            }

            // Redirect dengan pesan sukses
            toastr()->success('Pos berhasil diupdate');
            return redirect()->route('posts.index');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan
            toastr()->error('Terjadi kesalahan');
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Ambil post berdasarkan ID
            $post = Post::findOrFail($id);

            // Hapus file gambar jika ada
            if ($post->image) {
                $oldImagePath = storage_path('app/public/' . $post->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            // Lepaskan relasi tags
            $post->tags()->detach();

            // Hapus data post dari database
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
