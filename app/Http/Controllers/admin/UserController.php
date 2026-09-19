<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected $Userinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(UserRepositoryInterface $Userinterface){
        $this->Userinterface= $Userinterface;
    }
    public function index()
    {
        $allRecords = $this->Userinterface->all();
        return view('admin.Users.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Userinterface->create($validated);
            return redirect()->route('admin.users.index');
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
    public function edit(User $User)
    {
        return view('admin.users.edit',compact('User'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $User)
    {
         try {
            $validated = $request->validated();
            $this->Userinterface->update($User->id,$validated);
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $User)
    {
        try {
            $this->Userinterface->delete($User->id);
            return redirect()->route('admin.users.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
