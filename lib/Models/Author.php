<?php

namespace Lib\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    protected $table = 'authors';

    protected $connection = 'db';

    public function load($data)
    {
        $this->attributes['id'] = $data['id'] ?? null;
        $this->original['id'] = $data['id'] ?? null;
        $this->attributes['name'] = $data['name'] ?? null;
        $this->original['name'] = $data['name'] ?? null;
    }

}