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
 *
 **/

class Menu extends ModelBase{

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    private $id;

    /**
     * @Column(name="title", type="string", length=40)
     **/
    private $title;

    /**
     * @Column(name="intro", type="string", length=255)
     **/
    private $intro;

    /**
     * @Column(name="type", type="integer")
     **/
    private $type;

    /**
     * @Column(name="parent_id", type="integer")
     **/
    private $parent_id;

    /**
     * @Column(name="sort", type="integer")
     **/
    private $sort;

    /**
     * @Column(name="classify", type="boolean")
     **/
    private $classify;

    /**
     * @Column(name="outside_url", type="string", length=255)
     **/
    private $outside_url;

    /**
     * @Column(name="open_style", type="integer")
     **/
    private $open_style;

    /**
     * @Column(name="created", type="datetime")
     **/
    private $created;

    /**
     * @Column(name="is_hide", type="boolean")
     **/
    private $is_hide;

    /**
     * @Column(name="is_intranet", type="boolean")
     **/
    private $is_intranet;

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getIntro() {
        return $this->intro;
    }

    public function setIntro($intro) {
        $this->intro;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getParentId() {
        return $this->parent_id;
    }

    public function setParentId($pid) {
        $this->parent_id = $pid;
    }

    public function getSort() {
        return $this->sort;
    }

    public function setSort($sort) {
        $this->sort;
    }

    public function getClassify() {
        return $this->classify;
    }

    public function setClassify($classify) {
        $this->classify = $classify;
    }

    public function getOutsiteUrl() {
        return $this->outsite_url;
    }

    public function setOutsideUrl($outside_url) {
        $this->outside_url = $outside_url;
    }

    public function getOpenStyle() {
        return $this->open_style;
    }

    public function setOpenStyle($open_style) {
        $this->open_style = $open_style;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getIsHide() {
        return $this->is_hide;
    }

    public function setHide() {
        $this->is_hide = true;
    }

    public function setShow() {
        $this->is_hide = false;
    }

    public function IsIntranet() {
        return $this->is_intranet;
    }

    public function setIsIntranet($is_intranet) {
        $this->is_intranet = $is_intranet;
    }

    static public function getList($page=1, $pagesize=20, $asc=false) {
        $dql = sprintf(
            'SELECT n FROM %s n WHERE n.level > 0'.
            'ORDER BY n.id %s',
            get_called_class(),
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql)->setMaxResults($pagesize)->setFirstResult($pagesize*($page-1));
        return $query->useQueryCache(false)->getResult();
    }

}
