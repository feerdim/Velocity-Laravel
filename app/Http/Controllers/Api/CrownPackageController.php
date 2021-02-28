<?php

namespace App\Http\Controllers\Api;

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
        $this->middleware('auth:api');
    } 

    /**
     * get crown_package
     *
     * @return void
     */
    public function index()
    {
        $crown_packages = CrownPackage::orderBy('price', 'asc')->get();
        return response()->json([
            'success' => true,
            'crown_packages'    => $crown_packages
        ], 200);
    }
}
