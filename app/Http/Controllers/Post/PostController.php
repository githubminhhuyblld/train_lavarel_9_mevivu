<?php

namespace App\Http\Controllers\Post;

use App\Constants\Enum\Status;
use App\Constants\Entity\BaseEntityManager;
use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return Post::class;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('status', 'ACTIVE')->get();

        return view('user.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("user.posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $post = new Post();
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->slug = $request['slug'];
        $post->excerpt = $request['excerpt'];
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $post->image = 'images/' . $imageName;
        }
        if ($request->input('publish') === "CREATE_PUBLISH") {
            $post->posted_at = now();
            $post->save();
            return response()->json(['message' => 'Created and published successfully'], 200);
        } else {
            $post->save();
            return response()->json(['message' => 'Created successfully'], 200);
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
        return "Post detail";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        return view("user.posts.edit", compact('post'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->slug = $request->input('title');
        $post->excerpt = $request->input('excerpt');

        if ($request->hasFile('image')) {
            $oldImagePath = public_path($post->image);
            if ($post->image && File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $post->image = 'images/' . $imageName;
        }
        $post->save();
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            response()->json(['message' => 'Post Id: ' . $id . "Not Found"], 200);
        }
        $this->updateAttribute($id, 'status', Status::DELETED);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
