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
     * @OneToMany(targetEntity="MenuContent", mappedBy="target")
     **/
    public $translations;

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
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getDefaultTitle() {
        $default = \GlobalEnv::get('translation.default');
        foreach ($this->translations as $tra) {
            if ($tra->lang == $default) {
               return $tra->title;
            }
        }
        return null;
    }

    public function getTitle() {
        $default_translation = null;
        $default = \GlobalEnv::get('translation.default');
        $lang_code = \GlobalEnv::get('app')->getCookie('lang.code');
        $lang = Lang::getByCode($lang_code);
        foreach ($this->translations as $tra) {
            if ($tra->lang == $default) {
                $default_translation = $tra;
            }
            if ($tra->lang == $lang && !empty($tra->title)){
                return $tra->title;
            }
        }
        return $default_translation->title;
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

    public function translate($code) {
        foreach($this->translations as $tras) {
            if ($tras->isCode($code)) {
                return $tras;
            }
        }
        $lang = Lang::getByCode($code);
        $tras = new MenuContent();
        $tras->lang = $lang;
        $tras->target = $this;
        $tras->title = '';
        $tras->save();
        return $tras;
    }

    public function isParent() {
        return $this->parent == null;
    }

    public function hasSub() {
        $sub_menus = $this->_sub_menus->filter(function ($one) {
            return $one->is_deleted == 0;
        });
        return $sub_menus->count();
    }

    public function removeElement(Menu $sub) {
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
            $sub_menus = self::sortMenu($sub_menus->toArray());
        }
        return $sub_menus;
    }

    static public function sortMenu($menus) {
        usort($menus, function($one, $two){
            if ($one->sort == $two->sort) return 0;
            return ($one->sort > $two->sort) ? 1 : -1;
        });
        return $menus;
    }

    static public function getFirstMenu() {
        $top_menu_types = array(0, 1, 2);
        $menus = self::sortMenu(self::getByTypes($top_menu_types));
        return array_shift($menus);
    }

    static public function getByTypes($types=array()) {
        $all_menus = self::all();
        $result = array_filter($all_menus, function ($one) use($types) {
            return in_array($one->type, $types);
        });
        return $result;
    }

    static public function listAdminMenus() {
        $types = array(0, 1, 2);
        $top_menus = self::getTopMenus();
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

    static public function getListableMenus() {
        $listable_array = array(0, 2);
        return self::getByTypes($listable_array);
    }

    static public function getChildren() {
        $all_menus = self::all();
        $children = array_filter($all_menus, function($one) {
            return $one->parent != null;
        });
        return $children;
    }

    static public function getTopMenus($all=true) {
        $all_menus = self::all();
        $top_menus = array_filter($all_menus, function($one) use ($all) {
            $condition = $one->parent == null;
            if ($all == false) {
                $condition = $condition && $one->is_show == true;
            }
            return $condition;
        });
        $top_menus = self::sortMenu($top_menus);
        return $top_menus;
    }
}
