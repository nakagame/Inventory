<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;

class AdminController extends Controller
{
    private $admin;
    private $user;

    public function __construct(Admin $admin, User $user) {
        $this->admin = $admin;
        $this->user  = $user;
    }

    public function index(Request $request) {
        $role = $request->input('role', '');
        $query = $this->user->orderBy('deleted_at')->orderBy('role', 'desc')->withTrashed();

        if($role) {
            $query->where('role', $role);
        }

        $all_users = $query->paginate(15);

        return view('admin.index')->with('all_users', $all_users);
    }

    public function update($id) {   
        $this->user->onlyTrashed()->find($id)->restore();
        return redirect()->back();
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    }

    public function forceDeleteActive($id) {
        $this->user->find($id)->forceDelete();
        return redirect()->back();
    }

    public function forceDeleteInactive($id) {
        $this->user->onlyTrashed()->find($id)->forceDelete();
        return redirect()->back();
    }

    public function searchUser() {
        if (request('search')) {
            $users = $this->user->orderBy('deleted_at')->orderBy('role', 'desc')->withTrashed()->where('name', 'like', '%' . request('search') . '%')->paginate(10);
        } else {
            $users = $this->user->orderBy('deleted_at')->orderBy('role', 'desc')->paginate(10);
        }
    
        return view('admin.search')->with('users', $users);
    }
}
