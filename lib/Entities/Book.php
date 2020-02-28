<?php

namespace Lib\Entities;

class Book {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Author[]
     */
    private $authors;

    public function load($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->authors = [];
        foreach ($data['authors'] as $authorData){
            $author = new Author();
            $author->load($authorData);
            $this->authors[] = $author;
        }
    }

}