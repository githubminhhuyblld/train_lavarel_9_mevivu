<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Manager\Post\PostManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\DataTables;
use Illuminate\View\View;

class PostController extends Controller
{
    private PostManager $postManager;

    public function __construct(PostManager $postManager)
    {
        $this->postManager = $postManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {

        return view('user.posts.index');
    }

    public function getPosts(DataTables $dataTables): JsonResponse
    {
        $searchCriteria = [
            'title' => request('title'),
            'excerpt' => request('excerpt'),

        ];
        $query = $this->postManager->searchQuery($searchCriteria);
        $query->orderBy('created_at', 'desc');

        return $dataTables->eloquent($query)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view("user.posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     *
     * @return JsonResponse
     */
    public function store(PostRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $data = [
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'slug' => $request['slug'],
            'excerpt' => $request['excerpt'],
            'image' => $request['image'],
            'is_featured' => $request['is_featured'],
            'publish' => $request->input('publish'),
        ];

        $post = $this->postManager->createPost($data);
        if ($post->posted_at) {
            return response()->json(['message' => 'Created and published successfully'], 200);
        } else {
            return response()->json(['message' => 'Created successfully'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(int $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return  View
     */
    public function edit(int $id): View
    {
        $post = $this->postManager->findById($id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        return view("user.posts.edit", compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param int $id
     *
     * @return JsonResponse | RedirectResponse
     */
    public function update(PostRequest $request, int $id): JsonResponse|RedirectResponse
    {
        $validatedData = $request->validated();
        $post = $this->postManager->findById($id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->slug = $request->input('slug');
        $post->excerpt = $request->input('excerpt');
        $post->is_featured = $request->input('is_featured');
        if ($request->input('image') != $post->image) {
            $post->image = $request->input('image');
        }
        $this->postManager->update($id, $post);
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $post = $this->postManager->findById($id);
        if (!$post) {
            response()->json(['message' => 'Post Id: ' . $id . "Not Found"], 404);
        }
        $this->postManager->remove($id);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
