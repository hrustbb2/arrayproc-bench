<?php

namespace Lib\Entities;

class Author {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function load($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }


}