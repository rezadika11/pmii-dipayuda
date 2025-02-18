<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function datatable()
    {
        $query = User::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btnEdit = '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-success btn-edit" data-id="' . $row->id . '">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>';

                $btnDelete = '';
                if (Auth::user()->roles === 'admin' && Auth::user()->id !== $row->id) {
                    // Admin dapat menghapus semua pengguna, kecuali dirinya sendiri
                    $btnDelete = '<button type="button" class="btn btn-sm btn-danger btn-delete" 
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i> Hapus
                    </button>';
                }
                return $btnEdit . ' ' . $btnDelete;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function index()
    {
        return view('backend.admin.user.index');
    }

    public function create()
    {
        $data['roles'] = [
            'admin' => 'Admin',
            'editor' => 'Editor',
        ];
        return view('backend.admin.user.create', $data);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'roles' => 'required',
            'avatar' => 'image|mimes:jpeg,jpg,png|max:1024',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 4 karakter',
            'roles.required' => 'Hak user harus dipilih',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPEG, JPG, atau PNG',
            'avatar.max' => 'Gambar maksimal 1MB',
        ]);

        try {
            // Proses upload gambar
            // if ($request->hasFile('avatar')) {
            //     $file = $request->file('avatar');
            //     // Generate filename yang aman
            //     $filename = time() . '.' . $file->getClientOriginalExtension();

            //     // Simpan file ke direktori 'avatar' pada disk 'public'
            //     $path = $file->storeAs('avatar', $filename, 'public');

            //     // Debug log
            //     \Log::info("Path gambar yang diupload: " . $path);

            //     if (!$path) {
            //         throw new \Exception('Gagal menyimpan file.');
            //     }
            // } else {
            //     \Log::warning("Tidak ada gambar yang diupload.");
            // }

            // Menyimpan data pengguna ke dalam database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'roles' => $request->roles,
            ]);

            toastr()->success('User berhasil disimpan');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            // Menangani error
            toastr()->error('Terjadi kesalahan');
            report($e);
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function edit($id)
    {
        $data['user'] = User::find($id);
        $data['roles'] = [
            'admin' => 'Admin',
            'editor' => 'Editor',
        ];
        return view('backend.admin.user.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'roles' => ['required'],
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Format email salah',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'roles.required' => 'Hak user harus dipilih',
        ]);

        try {
            // Proses upload gambar

            $users = User::findOrFail($id);
            // if ($request->hasFile('avatar')) {
            //     $file = $request->file('avatar');

            //     // Generate safe filename
            //     $filename = time() . '.' . $file->getClientOriginalExtension();

            //     // Simpan file ke direktori 'thumbnail' pada disk 'public'
            //     $path = $file->storeAs('profiles', $filename, 'public');

            //     if (!$path) {
            //         throw new \Exception('Gagal menyimpan file.');
            //     }

            //     // Hapus gambar lama
            //     if ($users->picture) {
            //         $oldImagePath = storage_path('app/public/' . $users->picture);
            //         if (File::exists($oldImagePath)) {
            //             File::delete($oldImagePath);
            //         }
            //     }

            //     // Update kolom gambar
            //     $users->picture = $path;
            // }
            $users->update([
                'name' => $request->name,
                'email' => $request->email,
                'roles' => $request->roles,
                'password' => $request->password ? bcrypt($request->password) : $users->password,
            ]);

            toastr()->success('User berhasil diupdate');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            //throw $th;
            toastr()->error('Terjadi kesalahan');
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Ambil post berdasarkan ID
            $users = User::findOrFail($id);
            // Hapus data post dari database
            $users->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
