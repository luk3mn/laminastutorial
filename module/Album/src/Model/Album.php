<?php

namespace Album\Model;

class Album // Our Album entity object is a PHP class
{
    public $id;
    public $artist;
    public $title;

    // In order to work with laminas-db's TableGateway class, we need to implement the exchangeArray() method
    public function exchangeArray(array $data) // this method copies the data from the provided array to our entity's properties => We will add an input filter later to ensure the values injected are valid
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->artist = !empty($data['artist']) ? $data['artist'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
    }
}