<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('admin.profile.index');
    }

    /**
     * update photo
     *
     * @return void
     */
    public function updatePhoto(Request $request)
    {
        $this->validate($request,[
            'image'         => 'image|mimes:jpeg,jpg,png|max:3000',
        ]);
        $image_name = basename(auth()->user()->avatar).PHP_EOL;
        Storage::disk('local')->delete('public/users/'. $image_name);
        $image = $request->file('image');
        $image->storeAs('public/users', $image->hashName());
        User::where('id',auth()->user()->id)->update([
            'avatar' => $image->hashName()
        ]);
        
        return redirect()->route('admin.profile.index')->with(['success' => 'Foto Berhasil Diupdate!']);
    
    }
}