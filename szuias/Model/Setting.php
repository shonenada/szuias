<?php

/**
 * 网站设置
 * @author shonenada
 *
 **/

namespace Model;

/**
 * @Entity
 * @Table(name="setting")
 *
 **/

class Setting extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="object", type="string", length=50)
     **/
    public $object;

    /**
     * @Column(name="key", type="string", length=50)
     **/
    public $key;

    /**
     * @Column(name="value", type="string", length=250)
     **/
    public $value;

    /**
     * @Column(name="description", type="string", length=250)
     **/
    public $description;

    /**
     * @Column(name="sort", type="integer")
     **/
    public $sort;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    static public function get($obj, $key) {
        return self::findByKey($obj, $key)->value;
    }

    static public function findByKey($obj, $key) {
        return self::findOneBy(array('object' => $obj, 'key' => $key));
    }

    public function remove($flush = true) {
        // 防止误删
    }

}