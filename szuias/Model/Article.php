<?php

/**
 * 文章模型类
 * @author shonenada
 * 
 **/

namespace Model;

/** 
 * @Entity 
 * @Table(name="article")
 *
 * @property integer   $id
 * @property string    $title         标题
 * @property text      $content       内容
 * @property integer   $menu_id       菜单 ID
 * @property integer   $category_id   分类 ID
 * @property integer   $author_id     作者
 * @property integer   $last_editor   最后修改人
 * @property datetime  $created       发布日期
 * @property datetime  $edit_time     修改日期
 * @property integer   $view_count    浏览次数
 * @property boolean   $is_top        是否置顶
 * @property integer   $sort          顺序
 * @property string    $redirect_url  重定向 URL
 * @property integer   $open_style    窗口打开方式 (0: 原窗口打开，1: 新窗口打开)
 * @property integer   $display_style 是否可见
 * @property boolean   $is_deleted    是否已删除
 *
 **/

class Article extends ModelBase{

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
     * @Column(name="content", type="text")
     **/
    private $content;

    /**
     * @OneToOne(targetEntity="Menu")
     * @JoinColumn(name="menu_id", referencedColumnName="id", nullable=true)
     */
    private $menu;

    /**
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @Column(name="last_editor", type="integer")
     **/
    private $last_editor;

    /**
     * @Column(name="created", type="datetime")
     **/
    private $created;

    /**
     * @Column(name="edit_time", type="datetime")
     **/
    private $edit_time;

    /**
     * @Column(name="view_count", type="integer")
     **/
    private $view_count;

    /**
     * @Column(name="is_top", type="boolean")
     **/
    private $is_top;

    /**
     * @Column(name="sort", type="integer")
     **/
    private $sort;

    /**
     * @Column(name="redirect_url", type="string", length="255")
     **/
    private $redirect_url;

    /**
     * @Column(name="open_style", type="integer")
     **/
    private $open_style;

    /**
     * @Column(name="display_style", type="integer")
     **/
    private $display_style;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    private $is_deleted;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getMenuId() {
        return $this->menu_id;
    }

    public function setMenuId($mid) {
        $this->menu_id = $mid;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function setCategoryId($cid) {
        $this->category_id = $cid;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
    }

    public function getLastEditor() {
        return $this->last_editor;
    }

    public function setLastEditor($last_editor) {
         $this->last_editor = $last_editor;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function getEditTime() {
        return $this->edit_time;
    }

    public function setEditTime($edit_time) {
        return $this->edit_time = $edit_time;
    }

    public function getViewCount() {
        return $this->view_count;
    }

    public function setViewCount($view_count) {
        $this->view_count;
    }

    public function isTop() {
        return $this->is_top == true;
    }

    public function setTop() {
        $this->is_top = true;
    }

    public function setNotTop() {
        $this->is_top = false;
    }

    public function getSort() {
        return $this->sort;
    }

    public function setSort($sort) {
        $this->sort;
    }

    public function getRedirectUrl() {
        return $this->redirect_url;
    }

    public function setRedirectUrl($redirect_url) {
        $this->redirect_url = $redirect_url;
    }

    public function getOpenStyle() {
        return $this->open_style;
    }

    public function setOpenStyle($open_style) {
        $this->open_style = $open_style;
    }

    public function getDisplayStyle() {
        return $this->display_style;
    }

    public function setDisplayStyle($display_style) {
        $this->display_style = $display_style;
    }

    public function isDeleted() {
        return ($this->is_deleted == true);
    }

    public function delete() {
        $this->is_deleted = true;
    }

}
