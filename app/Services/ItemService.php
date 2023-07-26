<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Models\Article;
use App\Models\Item;
use Illuminate\Database\DatabaseManager;

class ItemService
{
    private $database;
    private $articleService;

    public function __construct(
        DatabaseManager $database, 
        ArticleService $articleService
    )
    {
        $this->database = $database;
        $this->articleService = $articleService;
    }

    public function getAll()
    {
        return Item::with("article")
            ->get();
    }

    public function create(array $params)
    {
        $articleId = $params["article_id"];
        if ($this->articleService->hasShelfLife($articleId) 
            && empty($params["shelf_life"])) {
            throw new BadRequestException("Shelf life is required for the chosen article");
        }
        // creating an item should increment the amount of items on the parent Article
        $item = new Item();

        $this->database->beginTransaction();
        try {
            $item->article_id = $articleId;
            $item->shelf_life = $params["shelf_life"];
            $item->save();

            // get article and increment amount
            $article = Article::findOrFail($item->article_id);
            $article->amount = $article->amount + 1;
            $article->save();
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollback();
            throw $e;
        }

        return Item::with("article")
            ->find($item->id);
    }

    public function getById(int $itemId)
    {
        $item = Item::with("article")
            ->findOrFail($itemId);
        return $item;
    }

    public function update(array $params, int $itemId)
    {
        $item = Item::with("article")->findOrFail($itemId);
        // After creation, article it's attached to can't be changed
        // Just delete the item and recreate. To maintain the state of the amount of items 
        // saved in the article row
        // $item->article_id = $params["article_id"];
        $articleId = $item->article_id;
        if ($this->articleService->hasShelfLife($articleId) 
            && empty($params["shelf_life"])) {
            throw new BadRequestException("Shelf life is required for the chosen article");
        }
        $item->shelf_life = $params["shelf_life"];
        $item->save();
        return $item;
    }

    public function delete(int $itemId)
    {
        $item = Item::findOrFail($itemId);
        // if it's found, reduce the amount of items in the attached article
        $this->database->beginTransaction();
        try {
            $article_id = $item->article_id;
            $article = Article::findOrFail($article_id);
            $article->amount = $article->amount - 1;
            $article->save();
            $item->delete();
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollback();
            throw $e;
        }
        // return true;
    }
}
