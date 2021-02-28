<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mmr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MmrController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:mmrs.index|mmrs.create|mmrs.edit|mmrs.delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mmrs = Mmr::latest()->get();
        return view('admin.mmr.index', compact('mmrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mmr.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:3000',
            'name'          => 'required|unique:mmrs',
            'min_rate'      => 'required|min:0',
            'max_rate'      => 'required|gte:min_rate'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/mmrs', $image->hashName());

        $mmr = Mmr::create([
            'image'       => $image->hashName(),
            'name'        => $request->input('name'),
            'min_rate'        => $request->input('min_rate'),
            'max_rate'        => $request->input('max_rate'),
        ]);


        if($mmr){
            //redirect dengan pesan sukses
            return redirect()->route('admin.mmr.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.mmr.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mmr $mmr)
    {
        return view('admin.mmr.edit', compact('mmr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mmr $mmr)
    {
        $this->validate($request,[
            'image'         => 'image|mimes:jpeg,jpg,png|max:3000',
            'name'          => 'required|unique:games,name,'.$mmr->id,
            'min_rate'      => 'required|min:0',
            'max_rate'      => 'required|gte:min_rate'
        ]);


        if ($request->file('image') == "") {
        
            $mmr = Mmr::findOrFail($mmr->id);
            $mmr->update([
                'name'        => $request->input('name'),
                'min_rate'        => $request->input('min_rate'),
                'max_rate'        => $request->input('max_rate'),
            ]);

        } else {
            //remove old image
            $image_name = basename($mmr->image).PHP_EOL;
            Storage::disk('local')->delete('public/mmrs/'. $image_name);

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/mmrs', $image->hashName());

            $mmr = Mmr::findOrFail($mmr->id);
            $mmr->update([
                'image'       => $image->hashName(),
                'name'        => $request->input('name'),
                'min_rate'    => $request->input('min_rate'),
                'max_rate'    => $request->input('max_rate'),
            ]);

        }

        if($mmr){
            //redirect dengan pesan sukses
            return redirect()->route('admin.mmr.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.mmr.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mmr = Mmr::findOrFail($id);
        $image_name = basename($mmr->image).PHP_EOL;
        Storage::disk('local')->delete('public/mmrs/'. $image_name);
        $mmr->delete();

        if($mmr){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
