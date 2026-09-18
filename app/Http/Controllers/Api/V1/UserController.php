<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() { return User::query()->select('id','name','email','role','created_at')->latest()->paginate(25); }
    public function update(Request $request, User $user)
    {
        $data=$request->validate(['name'=>'required|string|max:120','email'=>['required','email',Rule::unique('users')->ignore($user)],'role'=>['required',Rule::in(['superadmin','admin','operator','viewer'])],'password'=>'nullable|string|min:12|confirmed']);
        if (blank($data['password'] ?? null)) unset($data['password']); else $data['password']=Hash::make($data['password']);
        $user->update($data); return $user->only(['id','name','email','role']);
    }
}
