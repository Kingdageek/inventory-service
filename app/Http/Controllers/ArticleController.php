<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->articleService->getAll();
        return $this->success_response($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $params = $request->validated();
        $article = $this->articleService->create($params);
        return $this->success_response($article, "Article created successfully", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = $this->articleService->getById($id);
        return $this->success_response($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, int $id)
    {
        $params = $request->validated();
        $article = $this->articleService->update($params, $id);
        return $this->success_response($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deleted = $this->articleService->delete($id);
        return $this->success_response($deleted, "Article successfully deleted", 204);
    }
}
