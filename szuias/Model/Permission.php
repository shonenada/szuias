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
        'account' => array(1, '用户管理'),
        'menu' => array(2, '菜单管理'),
        'category' => array(3, '分类管理'),
        'setting' => array(4, '网站设置'),
        'profile' => array(5, '个人中心'),
        'data' => array(6, '数据管理')
    );

    public static $models_name = array(
        0 => 'content',
        1 => 'account',
        2 => 'menu',
        3 => 'category',
        4 => 'setting',
        5 => 'profile',
        6 => 'data'
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

    static public function getMidByType ($type) {
        $dql = sprintf(
            'SELECT n.mid FROM %s n '.
            'WHERE n.type = :type',
            get_called_class());
        $query = static::em()->createQuery($dql)->setParameter('type', $type);
        return $query->getResult();
    }

    static public function getByUid ($uid) {
        $menu_permissions = self::getMidByType(self::$types['menu']);
        $model_permissions = self::getMidByType(self::$types['model']);

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

    static public function authModel ($mid, $user=null) {
        if ($user == null) {
            $user = \GlobalEnv::get('user');
        }
        $app = \GlobalEnv::get('app');
        if ($user == null) 
            return $app->halt(403, "You have no permission!");

        if ($user->isAdmin())
            return true;

        $permit_mids = $user->getPermissionIds();
        $permit_models = $permit_mids['model'];
        if (in_array($mid, $permit_models)) {
            return true;
        }
        return $app->halt(403, "You have no permission!");
    }

}