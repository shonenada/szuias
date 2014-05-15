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
 * @property string     $title      分类名
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
     * @Column(name="title", type="string", length=40)
     **/
    public $title;

    /**
     * @OneToMany(targetEntity="CategoryContent", mappedBy="target")
     **/
    public $translations;

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
     * @OneToMany(targetEntity="Article", mappedBy="category")
     **/
    public $articles;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    public $is_deleted;

    public function __construct() {
        $this->is_deleted = 0;
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getTitle(){
        $default_translation = null;
        $default = \GlobalEnv::get('translation.default');
        $lang_code = \GlobalEnv::get('app')->getCookie('lang.code');
        $lang = Lang::getByCode($lang_code);
        foreach ($this->translations as $tra) {
            if ($tra->lang == $default && !empty($tra->title)) {
                $default_translation = $tra;
            }
            if ($tra->lang == $lang) {
                return $tra->title;
            }
        }
        return $default_translation->title;
    }

    public function getCount() {
        $temp = $this->articles->filter(function ($one) {
            return $one->is_deleted == 0;
        });
        return $temp->count();
    }

    public function delete() {
        $this->is_deleted = 1;
        $this->save();
    }

}