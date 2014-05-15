<?php

/**
 * 翻译模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="article_content")
 *
 * @property integer   $id
 * @property integer   $target_id     目标 id
 * @property string    $title         标题
 * @property text      $content       内容
 *
 **/

class ArticleContent extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @OneToOne(targetEntity="Lang")
     * @JoinColumn(name="lang_id", referencedColumnName="id")
     */
    public $lang;

    /**
     * @ManyToOne(targetEntity="Article", inversedBy="translation")
     * @JoinColumn(name="target_id", referencedColumnName="id")
     **/
    public $target;

    /**
     * @Column(name="title", type="string", length=50)
     **/
    public $title;

    /**
     * @Column(name="content", type="text")
     **/
    public $content;

    public function isCode($code) {
        return $this->lang->code == $code;
    }

    static public function searchArticles ($keyword) {
        $builder = static::em()->createQueryBuilder();
        $builder = $builder->select('n')->from(get_called_class(), 'n');
        $builder = $builder->where('n.title LIKE :keyword');
        $builder = $builder->setParameter('keyword', '%' . $keyword . '%');
        $query = $builder->getQuery();
        $query = $query->setFirstResult(0);
        return $query->useQueryCache(false)->getResult();
    }

}
