<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Database\DatabaseManager;

class CategoryService {

    private $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    public function getAll() {
        return Category::all();
    }

    public function create(array $params) {
        $category = new Category();
        $category->name = $params["name"];
        $category->save();
        return $category;
    }

    public function getById(int $category_id) {
        $category = Category::findOrFail($category_id);
        return $category;
    }

    public function update(array $params, int $category_id) {
        $category = Category::findOrFail($category_id);
        $category->name = $params["name"];
        $category->save();
        return $category;
    }

    public function delete(int $category_id) {
        $category = Category::findOrFail($category_id);
        // This has to cascade to all the articles attached to it.
        // and the items attached to the articles to maintain DB state
        $this->database->beginTransaction();
        try {
            $articles = $category->articles();
            foreach($articles as $article) {
                $article->items()->delete();
            }
            $articles->delete();
            $category->delete();
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollback();
            throw $e;
        }
    }
}