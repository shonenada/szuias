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
 * @property integer   $lang          语言
 * @property inteegr   $target_id     目标 id
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
     * @Column(name="lang", type="integer")
     **/
    public $lang;

    /**
     * @Column(name="target_id", type="integer")
     **/
    public $target_id;

    /**
     * @ManyToOne(targetEntity="Article", inversedBy="translation")
     * @JoinColumn(name="target_id", referencedColumnName="id")
     **/
    public $article_info;

    /**
     * @Column(name="title", type="string", length=50)
     **/
    public $title;

    /**
     * @Column(name="content", type="text")
     **/
    public $content;

}
