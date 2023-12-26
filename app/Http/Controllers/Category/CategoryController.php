<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Manager\Category\CategoryManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    private CategoryManager $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('user.categories.index');
    }

    public function getData(DataTables $dataTables): JsonResponse
    {
        $searchCriteria = [
            'id' => request('id'),
            'title' => request('name'),

        ];
        $query = $this->categoryManager->searchQuery($searchCriteria);

        return $dataTables->eloquent($query)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view("user.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $data = [
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
        ];
        $this->categoryManager->create($data);
        return response()->json(['message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): View
    {
        $category = $this->categoryManager->findById($id);

        if (!$category) {
            return redirect()->route('posts.index')->with('error', 'Post not found.');
        }

        return view("user.categories.edit", compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $validatedData = $request->validated();
        $category = $this->categoryManager->findById($id);

        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'Post not found.');
        }

        $category->name = $validatedData['name'];
        $category->slug = $validatedData['slug'];
        $this->categoryManager->update($id, $category);
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
        $category = $this->categoryManager->findById($id);
        if (!$category) {
            response()->json(['message' => 'Category Id: ' . $id . "Not Found"], 404);
        }
        $this->categoryManager->remove($id);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function massDelete(Request $request): JsonResponse
    {
        $ids = $request->ids;
        $this->categoryManager->removeByIds($ids);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }

}
