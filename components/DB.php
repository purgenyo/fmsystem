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
    private $_result = [];
    public  $attributes = [];
    public  $tableName;
    private $_select;
    protected $_fields;
    public $schema;

    /**
     *
     * @todo check if exist table, fields
     * DB connection constructor.
     * Set config
     *
     */
    function __construct(){
        mysqli_report(MYSQLI_REPORT_STRICT);
        $config_db = application::app()->getConfig()['db'];
        $connection = null;
        try {
            $connection = new mysqli();
            //$connection->real_connect();

            $connection->real_connect($config_db['host'], $config_db['login'], $config_db['password'], $config_db['db_name']);
            $connection->set_charset('utf8');
            $this->setConnection( $connection );
        } catch (\Exception $e ) {
            /**
             * @todo add exeption class
             */
            echo $e->getLine() . ': ' . $e->getFile() . '<br>';
            echo $e->getCode() .': '. $e->getMessage();
            die;
        }
    }

    /**
     * @param $conn
     */
    private function setConnection( $conn ){
        $this->_connection = $conn;
    }

    /**
     * @return mixed
     */
    protected function getConnection(){
        return $this->_connection;
    }

    /**
     * @param $sql
     * @return array
     */
    private function _query( $sql ){
        $this->_result = [];
        $result = $this->_connection->query( $sql );
        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc())
                $this->_result[] = $row;
        }
    }

    public function select( $select = [] ){
        $this->_select = implode(',', $select);
    }

    public function one(){
        if(isset($this->_result[0]))
            return $this->_result[0];
        return $this->_result;
    }

    public function all(){
        return $this->_result;
    }


    public function beforeInsert() {}

    public function insert( $fields ){
        $this->_fields = $fields;
        $this->beforeInsert();
        $toFields = (implode(',', $this->attributes));
        $toBind = trim(str_repeat('?,', count($this->attributes)), ',');
        $stmt = $this->_connection->prepare("INSERT INTO {$this->tableName}($toFields) VALUES ($toBind)");
        /**
         * @todo add from schema!
         */
        $tmp = [$this->schema];
        foreach($this->_fields as $key => $value) $tmp[] = &$this->_fields[$key];
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        if(!$stmt->execute()){
            return false;
        }
        return true;
    }

    /*
     * @todo add all sql methods
     * Construct sql query
     */
    public function find(){
        $this->_result = [];
        if(empty($this->_select)){
            $this->_select = '*';
        }
        $this->_query("SELECT {$this->_select} FROM `{$this->tableName}`");
    }

    public function pk( $pk ){
        $pk = intval($pk);
        $this->_query( "SELECT {$this->_select} FROM `{$this->tableName}` where {$this->pk}={$pk}" );
        return isset($this->_result[0]) ? $this->_result[0] : [];
    }

    public function delete( $pk ){
        $pk = intval($pk);
        $stmt = $this->_connection->prepare("DELETE FROM {$this->tableName} WHERE `book_id`=(?)");
        $stmt->bind_param('i', $pk);
        if(!$stmt->execute()){
            return false;
        }
        return $this->_connection->affected_rows;
    }
}