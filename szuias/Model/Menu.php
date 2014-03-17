<?php

/**
 * 菜单模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="menu")
 *
 * @property integer   $id          
 * @property string    $title         菜单标题
 * @property string    $intro         菜单介绍
 * @property integer   $type          菜单的类型  0：为节点菜单；1：为单页内容；：为多记录列表；3：为外部URL；
 * @property integer   $parent_id     父级菜单ID
 * @property integer   $sort          菜单排序
 * @property boolean   $classify      是否必须归档
 * @property string    $outside_url   外部链接
 * @property integer   $open_style    打开方式  0：原窗口打开；1：新窗口打开。
 * @property datetime  $created       创建时间
 * @property boolean   $is_hide       是否可见  0：可见；1：不可见
 * @property boolean   $is_intranet   仅内部访问  0：否；  1：是
 * @property boolean   $is_deleted    是否被删除  0：否；  1：是
 *
 **/

class Menu extends ModelBase{

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="title", type="string", length=40)
     **/
    public $title;

    /**
     * @Column(name="intro", type="string", length=255)
     **/
    public $intro;

    /**
     * @Column(name="type", type="integer")
     **/
    public $type;

    /**
     * @OneToMany(targetEntity="Menu", mappedBy="parent")
     **/
    public $sub_menus;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="sub_menus")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    public $parent;

    /**
     * @OneToMany(targetEntity="Article", mappedBy="menu")
     **/
    public $articles;

    /**
     * @Column(name="sort", type="integer")
     **/
    public $sort;

    /**
     * @Column(name="classify", type="boolean")
     **/
    public $classify;

    /**
     * @Column(name="outside_url", type="string", length=255)
     **/
    public $outside_url;

    /**
     * @Column(name="open_style", type="integer")
     **/
    public $open_style;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    /**
     * @Column(name="is_hide", type="boolean")
     **/
    public $is_hide;

    /**
     * @Column(name="is_intranet", type="boolean")
     **/
    public $is_intranet;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    private $is_deleted;


    public function removeSubMenu(Menu $sub) {
        $this->sub_menus->remvoeElement($sub);
    }

    public function hide() {
        $this->is_hide = true;
    }

    public function show() {
        $this->is_hide = false;
    }

    public function delete() {
        $this->is_deleted = true;
        $this->save();
    }

    public function __construct() {
        $this->sub_menus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    static public function getTopMenus() {
        $allMenus = self::all();
        $topMenus = array_filter($allMenus, function($one) {
            return $one->parent == null;
        });
        return $topMenus;
    }
}
