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

    public function isCode($code) {
        return $this->lang->code == $code;
    }

}
