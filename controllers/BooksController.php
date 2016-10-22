<?php

namespace controllers;
use models\Book;

/**
 * Class BooksController
 * @package controllers
 */

class BooksController extends Controller {

    public function index(){
        $model = new Book;
        var_dump($model->getItems());
    }

    public function create() {
        echo 'action CREATE';
    }

    public function read() {
        echo 'action read';
    }

    public function update() {
        echo 'action update';
    }

    public function delete() {
        echo 'action delete';
    }
}