<?php

/**
 * 文件模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="file")
 *
 * @property integer   $id          
 * @property integer   $article_id    文章 id
 * @property string    $real_name     文件原名
 * @property integer   $address       附件地址
 * @property string    $type          附件类型
 * @property integer   $file_size     文件大小
 * @property integer   $uploader_id   上传者 id
 * @property datetime  $created       创建时间
 *
 **/

class File extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @ManyToOne(targetEntity="Article", inversedBy="files")
     * @JoinColumn(name="article_id", referencedColumnName="id")
     **/
    public $article;

    /**
     * @Column(name="real_name", type="string", length=255)
     **/
    public $real_name;

    /**
     * @Column(name="address", type="string", length=500)
     **/
    public $address;

    /**
     * @Column(name="type", type="string", length=15)
     **/
    public $type;

    /**
     * @Column(name="file_size", type="integer")
     **/
    public $file_size;

    /**
     * @Column(name="uploader_id", type="integer")
     **/
    public $uploader_id;

    /**
     * @Column(name="created", type="datetime")
     **/
    public $created;

    static public function getTop() {
        $nums = Setting::get('index_slider', 'nums');
        $source = Setting::get('index_slider', 'source');
        $fresh = Setting::get('index_slider', 'fresh_time');
        if ($fresh > 0) {
            $time_diff = time() - $fresh * 24 * 3600;
        }
        else {
            $time_diff = 0;
        }
        if ($source == 0) {
            $dql = sprintf(
                'SELECT n FROM %s n '.
                'WHERE n.created >= %s AND n.is_deleted = 0'.
                'ORDER BY n.created desc',
                get_called_class(),
                $time_diff
            );
            $query = static::em()->createQuery($dql)->setMaxResults($nums);
            return $query->useQueryCache(false)->getResult();
        }
        else {
            $result = array();
            $menu = Menu::find($source);
            $articles = $menu->articles;
            foreach ($articles as $art) {
                if ($art->is_deleted == 1)
                    continue;
                $result = array_merge($result, $art->files->toArray());
                if (count($result) >= $nums)
                    break;
            }
            return $result;
        }
    }

}
