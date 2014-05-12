<?php

/**
 * 翻译模型类
 * @author shonenada
 * 
 */

namespace Model;

/** 
 * @Entity 
 * @Table(name="menu_content")
 *
 * @property integer   $id
 * @property integer   $lang_id          语言
 * @property inteegr   $target_id     目标 id
 * @property string    $title         标题
 *
 **/

class MenuContent extends ModelBase {

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="lang_id", type="integer")
     **/
    public $lang_id;

    /**
     * @OneToOne(targetEntity="Lang")
     * @JoinColumn(name="lang_id", referencedColumnName="id")
     */
    public $lang;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="translation")
     * @JoinColumn(name="target_id", referencedColumnName="id")
     **/
    public $target;

    /**
     * @Column(name="title", type="string", length=50)
     **/
    public $title;

}
