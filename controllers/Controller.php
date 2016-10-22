<?php

namespace controllers;

/**
 * Class Controller
 * @package controllers
 */

class Controller
{
	protected $controller = 'books';
    protected $action = 'index';

	public function init(){

		if(isset($_GET['q'])){
			$this->controller = $_GET['q'];
		}

		if(isset($_GET['a'])){
			$this->action = $_GET['a'];
		}

		$controller = $this->controller.'Controller';
        $controller[0] = strtoupper($controller[0]);
        $controller = '\controllers\\' . $controller;
		$current_controller = new $controller;
		$current_controller->{$this->action}();
	}

	public function render( $view_file, $data = false ) {
		if(file_exists('views/'.$view_file)){
			require('views/'.$view_file);
		} else {
			die('view not found');
		}
	}
}