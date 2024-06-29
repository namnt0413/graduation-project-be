<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = ['id','created_at','updated_at'];

    protected $table = 'subjects';

    public function cv()
    {
        return $this->belongsTo(CV::class);
    }

    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
