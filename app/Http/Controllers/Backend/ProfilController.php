<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $data['user'] = User::find($id);
        $data['roles'] = [
            'admin' => 'Admin',
            'editor' => 'Editor',
        ];
        return view('backend.profil', $data);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:4'],
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.string' => 'Email harus berupa string',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password minimal 4 karakter',
        ]);

        try {
            $user = User::find($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            toastr()->success('User berhasil diupdate');
            return redirect()->route('profil.index');
        } catch (\Exception $e) {
            //throw $th;
            report($e);
            toastr()->error('Terjadi kesalahan');
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
