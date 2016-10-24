<?php

namespace models;

use DateTime;

class Book extends \components\DB {

    public $pk = 'book_id';
    public $tableName = 'books';
    /**
     * @todo add types from schema!
     * @var string
     */
    public $schema = 'ssiiii';
    public $attributes = [
        'name',
        'author',
        'release_date',
        'pages_count',
        'create_date',
        'last_modify'
    ];

    /**
     * @param array $attributes
     * @param $pk
     * @return bool|\mysqli_result
     */
    public function updateByPk( $attributes = [], $pk ){
        return $this->update($attributes, $pk);
    }

    public function beforeInsert(){
        $this->_fields['release_date'] = strtotime($this->_fields['release_date']);
        $this->_fields['create_date']  = time();
        $this->_fields['last_modify']  = time();
    }

    public function getData( $pk ){
        $result = $this->pk($pk);
        if(empty($result))
            return [];
        $result['release_date'] = $this->dateFormat($result['release_date']);
        $result['create_date'] = $this->dateFormat($result['create_date'], 'd.m.Y H:m:s');
        $result['last_modify'] = $this->dateFormat($result['last_modify'], 'd.m.Y H:m:s');

        return $result;
    }


    public function getAll(){
        $result = $this->all();
        if(empty($result))
            return [];
        foreach ($this->all() as $key => $item) {
            $result[$key]['release_date'] = $this->dateFormat($item['release_date']);
            $result[$key]['create_date'] = $this->dateFormat($item['create_date'], 'd.m.Y H:m:s');
            $result[$key]['last_modify'] = $this->dateFormat($item['last_modify'], 'd.m.Y H:m:s');
        }
        return $this->all();
    }

    public function load( $data ){
        $forSave = [];
        foreach($this->attributes as $attribute){
            if(empty($data[$attribute]))
                continue;
            $forSave[$attribute] = $data[$attribute];
        }
        return $forSave;
    }

    public function dateFormat( $timestamp, $format = 'd.m.Y' ){
        $date = new DateTime();

        if(!$this->_isTimestamp($timestamp)){
            return '';
        }

        $date->setTimestamp($timestamp);
        return $date->format($format);
    }

    private function _isTimestamp( $timestamp ){
        return ((string) (int) $timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX)
        && (!strtotime($timestamp));
    }

}