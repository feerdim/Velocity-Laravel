<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:permissions.index']);
    } 

    /**
     * function index
     *
     * @return void
     */
    public function index()
    {
        $permissions = Permission::latest()->get();

        return view('admin.permission.index', compact('permissions'));
    }
}