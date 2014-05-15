<?php

/**
 * 语言模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="language")
 *
 * @property integer   $id          
 * @property string    $code
 * @property string    $desc
 *
 **/

class Lang extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="code", type="string", length=5)
     **/
    public $code;

    /**
     * @Column(name="desc", type="string", length=20)
     **/
    public $desc;

    static public function getByCode($code) {
        return self::findOneBy(array('code' => $code));
    }

}
