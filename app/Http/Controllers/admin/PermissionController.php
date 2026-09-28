<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionController extends Controller
{
    protected $Permissioninterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(PermissionRepositoryInterface $Permissioninterface){
        $this->Permissioninterface= $Permissioninterface;
    }
    public function index()
    {
        $allRecords = $this->Permissioninterface->all();
        return view('admin.Permissions.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Permissioninterface->create($validated);
            return redirect()->route('admin.permissions.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $Permission)
    {
        return view('admin.permissions.edit',compact('Permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $Permission)
    {
         try {
            $validated = $request->validated();
            $this->Permissioninterface->update($Permission->id,$validated);
            return redirect()->route('admin.permissions.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $Permission)
    {
        try {
            $this->Permissioninterface->delete($Permission->id);
            return redirect()->route('admin.permissions.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
