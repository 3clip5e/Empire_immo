<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (! Auth::attempt($credentials, $request->filled('remember')) ){
            return back()->withErrors(['email'=>'Identifiants invalides'])->withInput();
        }

        $user = Auth::user();

        // Génère code 6 chiffres
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Envoi mail
        Mail::to($user->email)->send(new TwoFactorCodeMail($code));

        // Déconnecter temporairement jusqu'à vérif 2FA
        Auth::logout();

        // Stocke user_id temporairement en session pour vérif 2FA
        session(['2fa:user:id' => $user->id]);

        return redirect()->route('2fa.verify')->with('status','Un code de vérification a été envoyé à votre email');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/');
    }
}
