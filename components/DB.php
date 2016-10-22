<?php

namespace components;

use core\application;
use mysqli;

/**
 * Class DB
 * @package components
 */
class DB
{

    private $_connection;
    public $tableName;

    /**
     * DB connection constructor.
     */
    function __construct()
    {
        $config_db = application::app()->getConfig()['db'];

        try {
            $connection = new mysqli();
            $connection->real_connect($config_db['host'], $config_db['login'], $config_db['password'], $config_db['db_name']);
            $connection->set_charset('utf8');

            $this->setConnection( $connection );
        } catch (\Exception $e ) {
            new \Exception('connection error');
        }


    }

    public function model(){

    }

    private function setConnection( $conn ){
        $this->_connection = $conn;
    }

    protected function getConnection() {
        return $this->_connection;
    }

    private function _query( $sql ){
        $result = $this->_connection->query( $sql );
        $result_data = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc())
                $result_data[] = $row;
        }

        return $result_data;
    }

    public function find() {
        return $this->_query( "SELECT * FROM `{$this->tableName}`" );
    }

}