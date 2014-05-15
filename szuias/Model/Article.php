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
     * @OneToMany(targetEntity="ArticleContent", mappedBy="target")
     **/
    public $translations;

    /**
     * @Column(name="menu_id", type="integer")
     **/
    private $menu_id;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="articles")
     * @JoinColumn(name="menu_id", referencedColumnName="id", nullable=true)
     */
    public $menu;

    /**
     * @Column(name="category_id", type="integer")
     **/
    private $category_id;

    /**
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     **/
    public $category;

    /**
     * @OneToMany(targetEntity="File", mappedBy="article")
     **/
    public $files;

    /**
     * @Column(name="author_id", type="integer")
     */
    public $author_id;

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
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
            if ($tra->lang == $default) {
                $default_translation = $tra;
            }
            if ($tra->lang == $lang && !empty($tra->title)) {
                return $tra->title;
            }
        }
        return $default_translation->title;
    }

    public function getDefaultContent() {
        $default = \GlobalEnv::get('translation.default');
        foreach ($this->translations as $tra) {
            if ($tra->lang == $default) {
               return $tra->content;
            }
        }
        return null;
    }

    public function getContent(){
        $default_translation = null;
        $default = \GlobalEnv::get('translation.default');
        $lang_code = \GlobalEnv::get('app')->getCookie('lang.code');
        $lang = Lang::getByCode($lang_code);
        foreach ($this->translations as $tra) {
            if ($tra->lang == $default) {
                $default_translation = $tra;
            }
            if ($tra->lang == $lang && !empty($tra->content)) {
                return $tra->content;
            }
        }
        return $default_translation->content;
    }

    public function translate($code) {
        foreach($this->translations as $tras) {
            if ($tras->isCode($code)) {
                return $tras;
            }
        }
        $lang = Lang::getByCode($code);
        $tras = new ArticleContent();
        $tras->lang = $lang;
        $tras->target = $this;
        $tras->title = '';
        $tras->content = '';
        $tras->save();
        return $tras;
    }

    static public function getListByTopMenu($size, $top_menu_id, $order_by=array(array('id', 'ASC'))) {
        $top_menu = Menu::find($top_menu_id);
        $records = array();
        if ($top_menu->hasSub()) {
            foreach($top_menu->_sub_menus as $m) {
                $list = self::getListByMenuId(1, $size, $m->id, $order_by);
                $records = array_merge($records, $list);
            }
        }
        else {
            $records = self::getListByMenuId(1, $size, $top_menu->id, $order_by);
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

    static public function getListByMenuId($page, $pagesize, $mid, $order_by=array(array('id', 'ASC'))) {
        $order_str = "";
        foreach($order_by as $o) {
            if (in_array(strtoupper($o[1]), array('ASC', 'DESC'))) {
                $order_str .= sprintf('n.%s %s%s', $o[0], $o[1], $o == $order_by[count($order_by) - 1] ? '' : ', ');
            }
        }
        $dql = sprintf(
            'SELECT n FROM %s n '.
            'WHERE n.menu_id = %s '.
            'AND n.is_deleted = false '.
            'AND n.is_hide = false '.
            'ORDER BY %s',
            get_called_class(),
            $mid, $order_str
        );
        $query = static::em()->createQuery($dql)->setMaxResults($pagesize)->setFirstResult($pagesize*($page-1));
        return $query->useQueryCache(false)->getResult();
    }

    static public function paginateWithMid($page, $pagesize, $mid, $order_by='id', $asc=true) {
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

    static public function countByMids ($mids=array()) {
        $dql = sprintf('SELECT count(n) FROM %s n WHERE n.menu_id in (%s) AND n.is_deleted = 0', get_called_class(), implode(',', $mids));
        $query = static::em()->createQuery($dql);
        return $query->useQueryCache(false)->getOneOrNullResult();
    }

    static public function get_in ($article_ids) {
        $dql = sprintf('SELECT n FROM %s n WHERE n.id in (%s) AND n.is_deleted = 0 ORDER BY n.id DESC', get_called_class(), $article_ids);
        $query = static::em()->createQuery($dql)->setMaxResults(10)->setFirstResult(0);
        return $query->useQueryCache(false)->getResult();
    }

    static public function getRandombyMids($mids=array()) {
        $count = self::countByMids($mids);
        $count = array_shift($count);
        if ($count <= 0)
            return null;
        $random_id = mt_rand(0, $count - 1);
        $dql = sprintf('SELECT n FROM %s n WHERE n.menu_id in (%s) AND n.is_deleted = 0', get_called_class(), implode(',', $mids));
        $query = static::em()->createQuery($dql)->setMaxResults(1)->setFirstResult($random_id);
        return $query->useQueryCache(false)->getOneOrNullResult();
    }

    static public function search($mid, $title='', $cid=null, $author_id=null, $post_form=null) {
        $builder = static::em()->createQueryBuilder();
        $builder = $builder->select('n')->from(get_called_class(), 'n')->where('n.menu_id = :mid')->setParameter('mid', $mid);
        $builder = $builder->leftJoin('n.translations', 'c');
        if ($title) {
            $builder = $builder->andWhere('c.title LIKE :search_title')->setParameter('search_title', '%'.$title.'%');
        }
        if ($cid) {
            $builder = $builder->andWhere('n.category_id = :cid')->setParameter('cid', $cid);
        }
        if ($author_id) {
            $builder = $builder->andWhere('n.author_id = :aid')->setParameter('aid', $author_id);
        }
        if ($post_form) {
            $builder = $builder->andWhere('n.created > :post')->setParameter('post', $post_form);
        }
        $query = $builder->getQuery();
        $pager = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        return $pager;
    }

    static public function searchArticles($keyword) {
        $builder = static::em()->createQueryBuilder()->select('n')->from(get_called_class(), 'n');
        $builder = $builder->leftJoin('n.translations', 'c');
        $builder = $builder->leftJoin('c.lang', 'l');
        $builder = $builder->where('c.title LIKE :keyword');
        $builder = $builder->andWhere('l.code = :lang_code');
        $builder = $builder->orderBy('n.is_top DESC, n.created', 'DESC');  # what the hell ??
        $builder = $builder->setParameter('keyword', '%'.$keyword.'%');
        $builder = $builder->setParameter('lang_code', \GlobalEnv::get('app')->getCookie('lang.code'));
        $query = $builder->getQuery()->setFirstResult(0);
        return $query->useQueryCache(false)->getResult();
    }

}
