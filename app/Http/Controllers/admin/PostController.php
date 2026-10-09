<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostController extends Controller
{
    protected $Postinterface;
    /**
     * Display a listing of the resource.
     */
    public function __construct(PostRepositoryInterface $Postinterface){
        $this->Postinterface= $Postinterface;
    }
    public function index()
    {
        $allRecords = $this->Postinterface->all();
        if (request()->has('page') && $allRecords->isEmpty() && $allRecords->currentPage() > 1) {
            return redirect()->route('Posts.index', ['page' => $allRecords->lastPage()]);
        }
        return view('admin.Posts.index', compact('allRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->Postinterface->create($validated);
            return redirect()->route('posts.index')->with('success','post created successfully.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $Post)
    {
        return view('admin.posts.show',compact('Post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $Post)
    {
        return view('admin.posts.edit',compact('Post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $Post)
    {
         try {
            $validated = $request->validated();
            $this->Postinterface->update($Post->id,$validated);
            return redirect()->route('posts.index')->with('success', 'post updated successfully.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $Post)
    {
        try {
            $this->Postinterface->delete($Post->id);
            return back()->with('success', 'Post deleted successfully.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function toggleStatus(Request $request){
        // dd($request->all());
        try {
            $recordId = $request->id;
            $this->Postinterface->toggleStatus($recordId);
            return response()->json(['success' => true, 'message' => 'Post status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to update Post status.']);
        }
    }
}
