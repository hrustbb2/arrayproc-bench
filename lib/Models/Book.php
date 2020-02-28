<?php

namespace Lib\Models;

use Illuminate\Database\Eloquent\Model;
use Lib\Models\Author;

class Book extends Model {

    protected $table = 'books';

    protected $connection = 'db';

    protected $fillable = [
        'id', 'name',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'relations', 'book_id', 'author_id', 'id', 'id');
    }

}