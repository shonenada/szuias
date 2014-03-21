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
     * @Column(name="article_id", type="integer")
     **/
    public $article_id;

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

}
