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

    public function is_code($code) {
        return $this->lang->code == $code;
    }

}
