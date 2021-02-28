<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrownPackage;
use Illuminate\Http\Request;

class CrownPackageController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:crown_packages.index|crown_packages.create|crown_packages.edit|crown_packages.delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crown_packages = CrownPackage::latest()->get();
        return view('admin.crown.index', compact('crown_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.crown.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:crown_packages',
            'price' => 'required'
        ]);

        $crown = CrownPackage::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
        ]);

        if($crown){
            //redirect dengan pesan sukses
            return redirect()->route('admin.crown_package.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.crown_package.index')->with(['error' => 'Data Gagal Disimpan!']);
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
    public function edit(CrownPackage $crown_package)
    {
        return view('admin.crown.edit', compact('crown_package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrownPackage $crown_package)
    {
        $this->validate($request, [
            'name' => 'required|unique:crown_packages,name,' . $crown_package->id,
            'price' => 'required'
        ]);

        $crown_package = CrownPackage::findOrFail($crown_package->id);
        $crown_package->update([
            'name' => $request->input('name'),
            'price' => $request->input('price')
        ]);

        if($crown_package){
            //redirect dengan pesan sukses
            return redirect()->route('admin.crown_package.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.crown_package.index')->with(['error' => 'Data Gagal Diupdate!']);
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
        $crown = CrownPackage::findOrFail($id);
        $crown->delete();

        if($crown){
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
