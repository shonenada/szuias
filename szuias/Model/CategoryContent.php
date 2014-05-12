<?php

/**
 * 翻译模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="category_content")
 *
 * @property integer   $id
 * @property integer   $lang          语言
 * @property inteegr   $target_id     目标 id
 * @property string    $title         标题
 *
 **/

class CategoryContent extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="lang", type="integer")
     **/
    public $lang;

    /**
     * @Column(name="target_id", type="integer")
     **/
    public $target_id;

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="translation")
     * @JoinColumn(name="target_id", referencedColumnName="id")
     **/
    public $category_info;

    /**
     * @Column(name="title", type="string", length="50")
     **/
    public $title;

}
