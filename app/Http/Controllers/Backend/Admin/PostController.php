<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
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
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

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
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

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
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
