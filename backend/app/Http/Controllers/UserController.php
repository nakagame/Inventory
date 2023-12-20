<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index($id) {
        $user = $this->user->findOrFail($id);
        return view('profiles.index')->with('user', $user);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'  => 'required|min:1|max:255',
            'email' => 'required|min:1|max:255',
            'role'  => 'required'
        ]);

        $user = $this->user->findOrFail($id);
        
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        $user->save();

        return redirect()->route('index');
    }
}
