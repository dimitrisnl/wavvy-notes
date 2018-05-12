<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	public $timestamps = true;

    protected $fillable = [
        'name', 'body', 'user_id', 'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}