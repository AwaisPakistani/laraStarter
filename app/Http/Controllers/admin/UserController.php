<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\{User,Role};
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
        // dd(auth()->user()->roles);
        $allRecords = $this->Userinterface->all();
        return view('admin.Users.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.Users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Userinterface->create($validated);
            return redirect()->route('users.index')->with('success','User created successfully.');
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
        $roles = Role::all();
        return view('admin.Users.edit',compact('User', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $User)
    {
        // dd($request->all());
         try {
            $validated = $request->validated();
            $this->Userinterface->update($User->id,$validated);
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
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
            return back()->with('success', 'User deleted successfully.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function toggleStatus(Request $request){
        // dd($request->all());
        try {
            $userId = $request->id;
            $this->Userinterface->toggleStatus($userId);
            return response()->json(['success' => true, 'message' => 'User status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to update user status.']);
        }
    }
}
