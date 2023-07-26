<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = "items";
    protected $fillable = [
        "article_id", "shelf_life"
    ];

    public function article() {
        return $this->belongsTo(Article::class, "article_id", "id");
    }
}
