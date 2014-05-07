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
 * @property integer   $type          菜单的类型  0：为节点菜单；1：为单页内容； 2：为多记录列表；3：为外部URL；
 * @property integer   $parent_id     父级菜单ID
 * @property integer   $sort          菜单排序
 * @property boolean   $classify      是否必须归档
 * @property string    $outside_url   外部链接
 * @property integer   $open_style    打开方式  0：原窗口打开；1：新窗口打开。
 * @property datetime  $created       创建时间
 * @property boolean   $is_show       是否可见  1：可见；0：不可见
 * @property boolean   $is_intranet   仅内部访问  0：否；  1：是
 * @property boolean   $is_deleted    是否被删除  0：否；  1：是
 *
 **/

class Menu extends ModelBase {

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
     * @ManyToOne(targetEntity="Menu", inversedBy="_sub_menus")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    public $parent;

    /**
     * @OneToMany(targetEntity="Menu", mappedBy="parent")
     **/
    public $_sub_menus;

    /**
     * @OneToMany(targetEntity="Article", mappedBy="menu")
     **/
    public $articles;

    /**
     * @OneToMany(targetEntity="Category", mappedBy="menu")
     **/
    public $_categories;

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
     * @Column(name="is_show", type="boolean")
     **/
    public $is_show;

    /**
     * @Column(name="is_intranet", type="boolean")
     **/
    public $is_intranet;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    private $is_deleted;

    public function __construct() {
        $this->is_deleted = false;
        $this->is_intranet = false;
        $this->is_show = true;
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->_sub_menus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->_categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function hide() {
        $this->is_hide = 1;
    }

    public function show() {
        $this->is_hide = 0;
    }

    public function delete() {
        $this->is_deleted = 1;
        $this->save();
    }

    public function getArticleNums() {
        $temp = $this->articles->filter(function ($one) {
            return $one->is_deleted == 0;
        });
        return $temp->count();
    }

    public function getCategories() {
        return $this->_categories->filter(function ($one) {
            return $one->is_deleted == 0;
        });
    }

    public function is_parent() {
        return $this->parent == null;
    }

    public function has_sub() {
        $sub_menus = $this->_sub_menus->filter(function ($one) {
            return $one->is_deleted == 0;
        });
        return $sub_menus->count();
    }

    public function remove_element(Menu $sub) {
        $this->_sub_menus->remvoeElement($sub);
    }

    public function getFirstSubMenu () {
        $subs = $this->getSubMenus();
        $subs = array_filter($subs, function ($one) {
             return in_array($one->type, array(1, 2));
        });
        return array_shift($subs);
    }

    public function getSubMenus () {
        $sub_menus = $this->_sub_menus->filter(function ($one) {
            return $one->is_deleted == 0;
        });
        if ($sub_menus){
            $sub_menus = self::sort_menu($sub_menus->toArray());
        }
        return $sub_menus;
    }

    static public function sort_menu($menus) {
        usort($menus, function($one, $two){
            if ($one->sort == $two->sort) return 0;
            return ($one->sort > $two->sort) ? 1 : -1;
        });
        return $menus;
    }

    static public function get_first_menu() {
        $top_menu_types = array(0, 1, 2);
        $menus = self::sort_menu(self::get_by_types($top_menu_types));
        return array_shift($menus);
    }

    static public function get_by_types($types=array()) {
        $all_menus = self::all();
        $result = array_filter($all_menus, function ($one) use($types) {
            return in_array($one->type, $types);
        });
        return $result;
    }

    static public function list_admin_menus() {
        $types = array(0, 1, 2);
        $top_menus = self::get_top_menus();
        $menus = array_filter($top_menus, function($one) use($types){
            if (in_array($one->type, $types)) {
                return true;
            }
            foreach($one->getSubMenus() as $s) {
                if (in_array($s->type, $types)){
                    return true;
                }
            }
            return false;
        });
        return $menus;
    }

    static public function get_listable_menus() {
        $listable_array = array(0, 2);
        return self::get_by_types($listable_array);
    }

    static public function get_children() {
        $all_menus = self::all();
        $children = array_filter($all_menus, function($one) {
            return $one->parent != null;
        });
        return $children;
    }

    static public function get_top_menus($all=true) {
        $all_menus = self::all();
        $top_menus = array_filter($all_menus, function($one) use ($all) {
            $condition = $one->parent == null;
            if ($all == false) {
                $condition = $condition && $one->is_show == true;
            }
            return $condition;
        });
        $top_menus = self::sort_menu($top_menus);
        return $top_menus;
    }
}
