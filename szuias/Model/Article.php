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
 * @property boolean   $is_hide       是否可见
 * @property boolean   $is_deleted    是否已删除
 *
 **/

class Article extends ModelBase {

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
     * @Column(name="content", type="text")
     **/
    public $content;

    /**
     * @Column(name="menu_id", type="integer")
     **/
    private $menu_id;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="menu")
     * @JoinColumn(name="menu_id", referencedColumnName="id", nullable=true)
     */
    public $menu;

    /**
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    public $category;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    public $author;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="last_editor", referencedColumnName="id")
     */
    public $editor;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    /**
     * @Column(name="edit_time", type="datetime")
     **/
    public $edit_time;

    /**
     * @Column(name="view_count", type="integer")
     **/
    public $view_count;

    /**
     * @Column(name="is_top", type="boolean")
     **/
    public $is_top;

    /**
     * @Column(name="sort", type="integer")
     **/
    public $sort;

    /**
     * @Column(name="redirect_url", type="string", length=255)
     **/
    public $redirect_url;

    /**
     * @Column(name="open_style", type="integer")
     **/
    public $open_style;

    /**
     * @Column(name="is_hide", type="boolean")
     **/
    public $is_hide;

    /**
     * @Column(name="is_deleted", type="boolean")
     **/
    public $is_deleted;

    public function __construct() {
        $this->is_deleted = false;
        $this->is_hide = false;
        $this->view_count = 0;
        $this->is_top = false;
        $this->open_style = 0;
        $this->sort = 0;
    }

    public function setTop() {
        $this->is_top = true;
    }

    public function setNotTop() {
        $this->is_top = false;
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

    static public function get_list_by_top_menu($size, $top_menu_id, $order_by='id', $asc=true) {
        $top_menu = Menu::find($top_menu_id);
        $records = array();
        foreach($top_menu->sub_menus as $m) {
            $list = self::get_list_by_menu_id(1, $size, $m->id, $order_by, $asc);
            $records = array_merge($records, $list);
        }
        uasort($records, function($one, $two) {
            if ($one == $two)
                return 0;
            return ($one->created > $two->created) ? -1 : 1;
        });
        if (count($records) > $size) {
            return array_slice($records, 0, $size);
        }
        else {
            return $records;
        }
    }

    static public function get_list_by_menu_id($page, $pagesize, $mid, $order_by='id', $asc=true) {
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'WHERE n.menu_id = %s '.
            'AND n.is_deleted = false '.
            'ORDER BY n.%s %s',
            get_called_class(),
            $mid,
            $order_by,
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql)->setMaxResults($pagesize)->setFirstResult($pagesize*($page-1));
        return $query->useQueryCache(false)->getResult();
    }

    static public function paginate_with_mid($page, $pagesize, $mid, $order_by='id', $asc=true) {
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'WHERE n.is_deleted = 0 AND '.
            'n.menu_id = %s '.
            'ORDER BY n.%s %s',
            get_called_class(),
            $mid,
            $order_by,
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql)->setFirstResult($pagesize*($page-1))->setMaxResults($pagesize);
        $pager = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        return $pager;
    }

}
