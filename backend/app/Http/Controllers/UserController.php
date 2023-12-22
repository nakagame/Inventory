<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    const LOCAL_FOLDER_PATH = 'public/profiles/';
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
            'role'  => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        $user = $this->user->findOrFail($id);
        
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if($request->image && $user->image) {
            $this->deleteImage($user->image);
            $user->image = $this->saveImage($request);
        } elseif ($request->image) {
            $user->image = $this->saveImage($request);
        }

        $user->save();

        if(Auth::user()->role === 3) {
            // admin
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('index');
        }
    }

    private function saveImage($request) {
        $img_name = time(). '.'. $request->image->extension();
        $request->image->storeAs(self::LOCAL_FOLDER_PATH, $img_name);
        return $img_name;
    }

    private function deleteImage($img_name) {
        $img_name = self::LOCAL_FOLDER_PATH. $img_name;
        if(Storage::disk('local')->exists($img_name)) {
            Storage::disk('local')->delete($img_name);
        }
    }
}
