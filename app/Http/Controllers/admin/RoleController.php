<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleController extends Controller
{
    protected $Roleinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(RoleRepositoryInterface $Roleinterface){
        $this->Roleinterface= $Roleinterface;
    }
    public function index()
    {
        $allRecords = $this->Roleinterface->all();
        return view('admin.Roles.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Roleinterface->create($validated);
            return redirect()->route('admin.roles.index');
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
    public function edit(Role $Role)
    {
        return view('admin.roles.edit',compact('Role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $Role)
    {
         try {
            $validated = $request->validated();
            $this->Roleinterface->update($Role->id,$validated);
            return redirect()->route('admin.roles.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $Role)
    {
        try {
            $this->Roleinterface->delete($Role->id);
            return redirect()->route('admin.roles.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
