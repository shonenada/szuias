<?php

namespace Model;

/** 
 * @Entity 
 * @Table(name="slider")
 *
 * @property integer   $id
 * @property integer   $sort
 * @property string    $title
 * @property string    $img_url
 * @property string    $target_url
 * @property datetime  $created
 * @property bollean   $is_deleted
 *
 **/

class Slider extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="sort", type="integer")
     **/
    public $sort;

    /**
     * @Column(name="title", type="string")
     **/
    public $title;

    /**
     * @Column(name="img_url", type="string")
     **/
    public $img_url;

    /**
     * @Column(name="target_url", type="string")
     **/
    public $target_url;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    public $is_deleted;

    public function __construct() {
        $this->is_deleted = 0;
    }

    static public function getSliders() {
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'ORDER BY n.sort ASC',
            get_called_class()
        );
        $query = static::em()->createQuery($dql);
        $result = $query->getResult();
        return $result;
    }

}