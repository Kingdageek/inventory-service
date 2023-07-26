<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";

    // amount property should not be mass-assignable. Should be view only
    // as the updating is handled internally as associated items are created & deleted
    protected $fillable = [
        "category_id", "name", "packaging", "has_shelf_life", 
        "min_amount", "checker"
    ];

    public function category() {
        return $this->belongsTo(Category::class, "category_id", "id");
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
