<?php

namespace components;

use core\application;
use mysqli;

/**
 * Class DB
 * @package components
 *
 * @var $_connection \mysqli
 *
 */
class DB
{

    private $_connection;
    private $_result = [];
    private $_select;
    private $_where = '';

    public  $attributes = [];
    public  $tableName;
    public  $pk;
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

    public function runSql( $sql ){
        if($result = $this->_connection->query( $sql ))
            return $result;
        else{
            echo new \Exception( $this->_connection->error );
            die;
        }
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

    public function all(){
        $this->find();
        return $this->_result;
    }


    public function beforeInsert() {}

    public function insert( $fields ){
        if(empty($fields)){
            echo new \Exception('empty fields');
            die;
        }
        var_dump($this->attributes);
        die;
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

    public function setWhere( $sqlQuery ){
        $this->_where = $sqlQuery;
    }

    public function addWhere( $sqlQuery ){
        $this->_where .= $sqlQuery;
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
        $queryString = "SELECT {$this->_select} FROM `{$this->tableName}`";
        if(!empty($this->_where)){
            $queryString .= ' WHERE ' . $this->_where;
        }
        $this->_query($queryString);
    }

    public function pk( $pk ){
        $pk = intval($pk);
        $this->_query( "SELECT {$this->_select} FROM `{$this->tableName}` WHERE {$this->pk}={$pk}" );
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

    public function update( $attributes, $pk = false ){

        if(empty($attributes)){
            echo new \Exception('params error');
            die;
        }

        $updates = [];
        if (count($attributes) > 0) {
            foreach ($attributes as $key => $value) {
                $value = $this->_connection->real_escape_string($value); // this is dedicated to @Jon
                $value = "'$value'";
                $updates[] = "$key = $value";
            }
        }
        $updates = implode(', ', $updates);

        if($pk!=false){
            $pk = intval($this->_connection->real_escape_string($pk));
            $this->addWhere( "{$this->pk} = $pk");
        }



        $sql = "UPDATE $this->tableName SET {$updates}";
        if(!empty($this->_where)){
            $sql .= " WHERE  {$this->_where}";
        }

        return $this->runSql($sql);
    }
}