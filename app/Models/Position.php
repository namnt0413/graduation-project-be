<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $table = 'positions';

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
