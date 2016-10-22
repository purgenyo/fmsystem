<?php

namespace models;

class Book extends \components\DB {

    public $tableName = 'books';

    public function getItems() {
        return $this->find();
    }

}