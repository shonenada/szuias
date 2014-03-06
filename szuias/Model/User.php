<?php

/**
 * 用户模型类
 * @author shonenada
 * 
 */

namespace Model;
use \RBAC\Authentication;

/** 
 * @Entity 
 * @Table(name="user")
 *
 * @property integer   $id
 * @property string    $username
 * @property string    $password
 * @property string    $email
 * @property string    $phone
 * @property datetime  $created
 * @property integer   $ia_admin
 * @property integer   $is_delete
 *
 **/

class User extends ModelBase{

    /**
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue
     **/
    private $id;

    /**
     * @Column(name="username", type="string", length=50)
     **/
    private $username;

    /**
     * @Column(name="password", type="string", length=50)
     **/
    private $password;

    /**
     * @Column(name="email", type="string", length=50)
     **/
    private $email;

    /**
     * @Column(name="phone", type="string", length=1)
     **/
    private $phone;

    /**
     * @Column(name="created", type="datetime")
     **/
    private $created;

    /**
     * @Column(name="is_admin", type="boolean")
     **/
    private $is_admin;

    /**
     * @Column(name="is_delete", type="boolean")
     **/
    private $is_delete;

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($raw, $salt) {
        $hashPassword = User::hashPassword($raw, $salt);
        $this->password = $hashPassword;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function __construct($username) {
        $this->username = $username;
    }

    public function checkPassword($rawPassword, $salt) {
        $password = User::hashPassword($rawPassword, $salt);
        return ($this->password == $password);
    }

    public function hasPermission($resource, $method) {
        $ptable = require(APPROOT . "permissions.php");
        $auth = new Authentication();
        $auth->load($ptable);
        return $auth->accessiable($this, $resource, $method);
    }

    static public function validateToken($user, $token, $salt) {
        $ip = $user->getIP();
        $lastLogin = $user->getLastLogin()->format('Y-m-d H:i:s');
        $hash = md5($lastLogin . "{" . $salt . "}" . $ip);
        if($hash == $token){
            return $user;
        }else{
            return NULL;
        }
    }

    static public function hashPassword($password, $salt) {
        $hash = md5("{$salt}{$password}{$salt}");
        return $hash;
    }

    static public function getList($page=1, $pagesize=20, $asc=false) {
        $dql = sprintf(
            'SELECT n FROM %s n WHERE n.level > 0'.
            'ORDER BY n.id %s',
            get_called_class(),
            $asc ? 'ASC' : 'DESC'
        );
        $query = static::em()->createQuery($dql)->setMaxResults($pagesize)->setFirstResult($pagesize*($page-1));
        return $query->useQueryCache(false)->getResult();
    }

    static public function findByUsername($username) {
        $query = static::query()->findBy(array('username' => $username));
        if ($query != null){
            return $query[0];
        }
        else {
            return null;
        }
    }

}
