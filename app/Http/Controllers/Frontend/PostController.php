<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($slug)
    {
        // Ambil berita berdasarkan slug
        $post = Post::with('category', 'tags', 'users')->where('slug', $slug)->first();

        $postsPopuler = Post::with('tags', 'category', 'users')
            ->where('is_published', 1)
            ->latest() // Mengurutkan berdasarkan tanggal publikasi terbaru
            ->take(5)
            ->get();

        // Jika berita ditemukan, tampilkan detailnya
        if ($post) {
            return view('frontend.post.index', compact('post', 'postsPopuler'));
        } else {
            // Jika berita tidak ditemukan, kembalikan ke halaman 404
            abort(404);
        }
    }

    public function postCategory($slug)
    {
        // Ambil kategori berdasarkan slug
        $category = Category::where('slug', $slug)->first();

        // Jika kategori ditemukan, tampilkan daftar berita yang terkait
        if ($category) {
            $posts = Post::with('tags', 'category', 'users')
                ->where('is_published', 1)
                ->where('category_id', $category->id)
                ->latest() // Mengurutkan berdasarkan tanggal publikasi terbaru
                ->paginate(12);
            // dd($posts);
            return view('frontend.post.category', compact('posts', 'category'));
        } else {
            // Jika kategori tidak ditemukan, kembalikan ke halaman 404
            abort(404);
        }
    }
}
