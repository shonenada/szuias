<?php

/**
 * 权限模型
 * @author shonenada
 *
 **/

namespace Model;

/**
 * @Entity
 * @Table(name="permission")
 *
 **/

class Permission extends ModelBase {

    public static $models = array(
        'content' => array(0, '内容管理'),
        'user' => array(1, '用户管理'),
        'menu' => array(2, '菜单管理'),
        'category' => array(3, '分类管理'),
        'index' => array(4, '首页管理'),
        'profile' => array(5, '个人中心'),
        'data' => array(6, '数据管理')
    );

    public static $types = array('model' => 0, 'menu' => 1);
    public static $type_name = array(0 => 'model', 1 => 'menu');

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    public $id;

    /**
     * @Column(name="uid", type="integer")
     **/
    public $uid;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="permissions")
     * @JoinColumn(name="uid", referencedColumnName="id", nullable=true)
     */
    public $user;

    /**
     * @Column(name="mid", type="integer")
     **/
    public $mid;

    /**
     * @Column(name="type", type="integer")
     **/
    public $type;

    static public function get_mid_by_type ($type) {
        $dql = sprintf(
            'SELECT n.mid FROM %s n '.
            'WHERE n.type = :type',
            get_called_class());
        $query = static::em()->createQuery($dql)->setParameter('type', $type);
        return $query->getResult();
    }

    static public function get_by_uid($uid) {
        $menu_permissions = self::get_mid_by_type(self::$types['menu']);
        $model_permissions = self::get_mid_by_type(self::$types['model']);

        $menu = array();
        $model = array();

        foreach($menu_permissions as $value) {
            $menu[] = $value['mid'];
        }

        foreach ($model_permissions as $value) {
            $model[] = $value['mid'];
        }
        return array('model' => ($model), 'menu' => ($menu));
    }


}