<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function showVerify()
    {
        if (! session('2fa:user:id')) return redirect('/login');
        return view('auth.verify-2fa');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $userId = session('2fa:user:id');
        $entry = TwoFactorCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (! $entry) {
            return back()->withErrors(['code'=>'Code invalide ou expiré']);
        }

        // Supprime le code
        $entry->delete();

        // Connecte l'utilisateur
        Auth::loginUsingId($userId);
        session()->forget('2fa:user:id');

        return redirect()->intended('/dashboard');
    }

    public function resend()
    {
        $userId = session('2fa:user:id');
        if (! $userId) return redirect('/login');
        $user = \App\Models\User::find($userId);
        if (! $user) return redirect('/login');

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        TwoFactorCode::create(['user_id'=>$user->id,'code'=>$code,'expires_at'=>now()->addMinutes(10)]);
        Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($code));

        return back()->with('status','Code renvoyé par e-mail');
    }
}
