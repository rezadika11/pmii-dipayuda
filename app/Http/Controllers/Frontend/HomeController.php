<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['postsPopuler'] = Post::with('tags', 'category', 'users')
            ->where('is_published', 1)
            ->latest() // Mengurutkan berdasarkan tanggal publikasi terbaru
            ->take(5)
            ->get();

        $data['newsItems'] = Post::where('is_published', 1)
            ->orderBy('published', 'desc') // Urutkan berdasarkan tanggal publikasi
            ->take(5) // Ambil 5 berita teratas
            ->get();

        // Ambil kategori "Berita Terbaru"
        $category = Category::where('name', 'berita')->first();

        // Jika kategori ditemukan, ambil berita terbaru dari kategori tersebut
        if ($category) {
            $data['latestNews'] = $category->posts()
                ->where('is_published', 1)
                ->orderBy('published', 'desc') // Pastikan kolom ini ada di tabel posts
                ->with('users')
                ->take(5) // Ambil 5 berita terbaru
                ->get();
        } else {
            $data['latestNews'] = collect(); // Kosong jika kategori tidak ditemukan
        }

        // Ambil kategori "Berita Terbaru"
        $opini = Category::where('name', 'opini')->first();

        // Jika kategori ditemukan, ambil berita terbaru dari kategori tersebut
        if ($opini) {
            $data['latestOpini'] = $opini->posts()
                ->where('is_published', 1)
                ->orderBy('published', 'desc') // Pastikan kolom ini ada di tabel posts
                ->with('users')
                ->take(6) // Ambil 5 berita terbaru
                ->get();
        } else {
            $data['latestOpini'] = collect(); // Kosong jika kategori tidak ditemukan
        }

        // Ambil kategori "Artikel Terbaru"
        $artikel = Category::where('name', 'artikel')->first();

        // Jika kategori ditemukan, ambil berita terbaru dari kategori tersebut
        if ($artikel) {
            $data['latestArtikel'] = $artikel->posts()
                ->where('is_published', 1)
                ->orderBy('published', 'desc') // Pastikan kolom ini ada di tabel posts
                ->with('users')
                ->take(5) // Ambil 5 berita terbaru
                ->get();
        } else {
            $data['latestArtikel'] = collect(); // Kosong jika kategori tidak ditemukan
        }

        // Ambil kategori "Artikel Terbaru"
        $agenda = Category::where('name', 'agenda')->first();

        // Jika kategori ditemukan, ambil berita terbaru dari kategori tersebut
        if ($agenda) {
            $data['latestAgenda'] = $agenda->posts()
                ->where('is_published', 1)
                ->orderBy('published', 'desc') // Pastikan kolom ini ada di tabel posts
                ->with('users')
                ->take(6) // Ambil 5 berita terbaru
                ->get();
        } else {
            $data['latestAgenda'] = collect(); // Kosong jika kategori tidak ditemukan
        }

        $data['kategoriList'] = Category::withCount('posts')->get();


        $data['post'] = Post::where('is_published', '1')->latest()->first();
        return view('frontend.home', $data);
    }
}
