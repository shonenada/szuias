<?php

/**
 * 分类模型
 * @author shonenada
 *
 **/

namespace Model;

/**
 * @Entity
 * @Table(name="category")
 *
 * @property integer    $id
 * @property integer    $menu_id    所属菜单的 ID
 * @property string     $name       分类名
 * @property integer    $sort       排序
 * @property integer    $creator    创建者
 * @property datetime   $created    创建日期
 * @property boolean    $is_deleted 是否已删除
 *
 **/

class Category extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="name", type="string", length=40)
     **/
    public $name;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="categories")
     * @JoinColumn(name="menu_id", referencedColumnName="id")
     **/
    public $menu;

    /**
     * @Column(name="sort", type="integer")
     **/
    public $sort;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="creator_id", referencedColumnName="id")
     */
    public $creator;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    public $is_deleted;

}