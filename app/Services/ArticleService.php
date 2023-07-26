<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\DatabaseManager;

class ArticleService
{
    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    public function getAll()
    {
        return Article::with("category")
            ->get();
    }

    public function create(array $params)
    {
        // amount of items can just be counted from the items relationship
        // instead of saving though
        $article = Article::create($params);
        return Article::with("category")
            ->find($article->id);
    }

    public function getById(int $articleId)
    {
        $article = Article::with("category")
            ->findOrFail($articleId);
        return $article;
    }

    public function update(array $params, int $articleId)
    {
        $article = Article::findOrFail($articleId);
        // The amount of items this has cannot be updated directly by the user
        // The update is internally handled
        $article->category_id = $params["category_id"];
        $article->name = $params["name"];
        $article->packaging = $params["packaging"];
        $article->has_shelf_life = $params["has_shelf_life"];
        $article->min_amount = $params["min_amount"];
        $article->checker = $params["checker"];
        $article->save();
        return Article::with("category")
            ->find($article->id);
    }

    public function delete(int $articleId)
    {
        $article = Article::findOrFail($articleId);
        // Cascade. Delete every article ITEM if article is deleted
        $this->database->beginTransaction();
        try {
            $article->items()->delete();
            // foreach($article->items as $item) {
            //     $item->delete();
            // }
            $article->delete();
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollback();
            throw $e;
        }
        // return true;
    }

    public function hasShelfLife($articleId)
    {
        $article = Article::findOrFail($articleId);
        return $article->has_shelf_life;
    }
}
