<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return User::query()->select('id','name','email','role','created_at')->when($request->search, fn ($q, $s) => $q->where(fn ($x) => $x->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%")))->latest()->paginate(min($request->integer('per_page',25),100));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name'=>'required|string|max:120','email'=>'required|email|unique:users,email','role'=>['required',Rule::in(['superadmin','admin','operator','viewer'])],'password'=>'required|string|min:12|confirmed']);
        $user = User::create([...$data, 'password' => Hash::make($data['password'])]);
        return response()->json($user->only(['id','name','email','role','created_at']), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate(['name'=>'required|string|max:120','email'=>['required','email',Rule::unique('users')->ignore($user)],'role'=>['required',Rule::in(['superadmin','admin','operator','viewer'])],'password'=>'nullable|string|min:12|confirmed']);
        if (blank($data['password'] ?? null)) unset($data['password']); else $data['password'] = Hash::make($data['password']);
        $user->update($data);
        if ($request->user()->is($user)) $user->tokens()->delete();
        return $user->only(['id','name','email','role','created_at']);
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($request->user()->is($user), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        abort_if($user->role === 'superadmin' && User::where('role','superadmin')->count() <= 1, 422, 'Minimal harus ada satu superadmin.');
        $user->tokens()->delete(); $user->delete(); return response()->noContent();
    }
}
