<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|confirmed|min:6',
            'role'=>'required|in:client,bailleur',
            'phone'=>'nullable|string'
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>$data['role'],
            'phone'=>$data['phone'] ?? null
        ]);

        // Optionally auto-login then 2FA (we'll redirect to login)
        return redirect()->route('login')->with('success','Inscription réussie. Connectez-vous.');
    }
}
