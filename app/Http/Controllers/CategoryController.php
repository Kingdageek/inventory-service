<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();
        return $this->success_response($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $params = $request->validated();
        $category = $this->categoryService->create($params);
        return $this->success_response($category, "Category created successfully", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = $this->categoryService->getById($id);
        return $this->success_response($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, int $id)
    {
        $params = $request->validated();
        $category = $this->categoryService->update($params, $id);
        return $this->success_response($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deleted = $this->categoryService->delete($id);
        return $this->success_response($deleted, "Category successfully deleted", 204);
    }
}
