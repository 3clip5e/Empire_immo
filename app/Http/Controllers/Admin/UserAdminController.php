<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function __construct(){ $this->middleware(['auth','can:admin']); }

    public function index(){ $users = User::paginate(20); return view('admin.users.index', compact('users')); }
    public function edit(User $user){ return view('admin.users.edit', compact('user')); }
    public function update(Request $r, User $user){
        $r->validate(['name'=>'required','email'=>'required|email','role'=>'required|in:client,bailleur,admin']);
        $user->update($r->only(['name','email','role','phone']));
        return redirect()->route('admin.users.index')->with('success','Utilisateur mis à jour');
    }
    public function destroy(User $user){ $user->delete(); return back()->with('success','Utilisateur supprimé'); }
}
