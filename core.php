<?php

namespace core;

/**
 * Main app Class core
 */

class application {

    /* Config Array */
    private static $_app;
    private $_config = [];

    /**
     * application constructor.
     * @param string $config_file
     */
    function __construct($config_file = 'config' )
    {
        $this->loadConfig();
        application::setApp($this);
    }

    /**
     * run app
     */
    public function run(){
        $this->load();
        $this->init();
    }

    /**
     * @param $className
     */
    private function load( $className = false ){

        /* Load components */
        require_once('components/DB.php');

        /* Load Models */
        require_once('models/Book.php');

        /* Load Controllers */
        require_once('controllers/Controller.php');
        require_once('controllers/BooksController.php');
    }

    /**
     * @param $app
     * @throws \Exception
     */
    private static function setApp($app)
    {
        if( empty(self::$_app) || $app===null){
            self::$_app = $app;
        }
        else {
            throw new \Exception('Error with start app');
        }
    }

    public static function app()
    {
        return self::$_app;
    }

     /**
     *
     * load config file
     *
     * @param string $file
     * @throws \Exception
     */
    public function loadConfig( $file = 'config' ) {

        $file = './config/' . $file . '.php';

        if(is_file($file)){
            $file = include $file;
            if(is_array($file)){
                $this->_config = array_merge($this->_config, $file);
            }
        } else {
            throw new \Exception('not found');
        }
    }

    public function getConfig() {
        return $this->_config;
    }

    /**
     * init controller
     */
    public function init(){
        $controller = new \controllers\Controller;
        $controller->init();
    }
}