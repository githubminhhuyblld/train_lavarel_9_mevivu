<?php

namespace App\Http\Controllers\Category;

use App\Constants\Enum\Status;
use App\Http\Controllers\Controller;
use App\Manager\Category\CategoryManager;
use App\Models\Category\Category;
use App\Models\Post\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function index():View
    {
        return view('user.categories.index');
    }

    public function getData(DataTables $dataTables): JsonResponse
    {
        $query = $this->categoryManager->getActiveCategoriesQuery();

        return $dataTables->eloquent($query)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        $post = Category::find($id);
        if (!$post) {
            response()->json(['message' => 'Category Id: ' . $id . "Not Found"], 404);
        }
        $this->categoryManager->remove($id);

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
