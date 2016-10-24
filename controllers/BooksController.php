<?php

namespace controllers;
use models\Book;

/**
 * Class BooksController
 * @package controllers
 */

class BooksController extends Controller {

    /**
     * insert
     */
    public function create() {
        $model = new Book;
        $forSave = $model->load($_POST);
        if($model->insert($forSave)){
            echo json_encode(['success'=>1]);
            die;
        }
        echo json_encode(['success'=>0]);
    }

    public function read() {
        $model = new Book;
        $model->select(['*']);

        if(isset($_GET['book_id']) && !empty($_GET['book_id'])){
            echo json_encode($model->getData($_GET['book_id']));
            die;
        }

        echo json_encode($model->getAll());
    }

    /**
     * @todo add update method
     */
    public function update() {
        $model = new Book;
        $book_id = false;
        $forUpdate = $model->load($_POST);
        if(isset($_GET['book_id']) && !empty($_GET['book_id'])){
            $book_id = $_GET['book_id'];
        }

        if($result = $model->updateByPk($forUpdate, $book_id )){
            echo json_encode(['success'=>1]);
            die;
        }

        echo json_encode(['success'=>0]);
    }
    
    public function delete() {
        if(!isset($_POST['book_id']) || empty($_POST['book_id'])){
            echo json_encode(['success'=>0]);
            die;
        }

        $model = new Book;
        if($model->delete($_POST['book_id'])){
            echo json_encode(['success'=>1]);
            die;
        }
        echo json_encode(['success'=>0]);
    }
}