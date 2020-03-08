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

    public function load($data)
    {
        $this->attributes['id'] = $data['id'] ?? null;
        $this->attributes['name'] = $data['name'] ?? null;
        $authors = [];
        foreach($data['author'] as $authorData){
            $author = new Author();
            $author->load($authorData);
            $authors[] = $author;
        }
        $this->attributes['authors'] = $authors;
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'relations', 'book_id', 'author_id', 'id', 'id');
    }

}