<?php

namespace RBAC;

class Authentication{

    private $ptable = array();
    private $redirect = array();

    public function allow(Role $role, $resource, $method){
        $this->record($role, $resource, $method, 'allow');
        return $this;
    }

    public function deny(Role $role, $resource, $method){
        $this->record($role, $resource, $method, 'deny');
        return $this;
    }

    public function accessiable(\Model\User $user=null, $resource=null, $method=null){

        $auth = function(Role $r=null, \Model\User $u=null){
            return $r ? $r->authenticate($u) : false;
        };

        $allow = array_filter($this->ptable, function($i) use ($user, $resource, $method, $auth){
            $pattern = sprintf("/^%s$/", str_replace('/', '\/', $i['resource']));
            preg_match($pattern, $resource, $matches);
            return $matches != null
                && ('*' == $i['method'] || in_array(strtolower($method), array_map(function ($one) { return strtolower($one); }, $i['method'])))
                && $auth($i['role'], $user)
                && $i['action'] == 'allow';
        });
        
        $deny = array_filter($this->ptable, function($i) use ($user, $resource, $method, $auth){
            $pattern = sprintf("/^%s$/", str_replace('/', '\/', $i['resource']));
            preg_match($pattern, $resource, $matches);
            return $matches != null
                && ('*' == $i['method'] || in_array(strtolower($method), array_map(function ($one) { return strtolower($one); }, $i['method'])))
                && $auth($i['role'], $user)
                && $i['action'] == 'deny';
        });

        return (!empty($allow) && empty($deny));

    }

    public function load($ptable){
        $allow = $ptable['allow'];
        $deny = $ptable['deny'];
        foreach($allow as $p){
            $role = $p[0];
            $resource = $p[1];
            $method = $p[2];
            if (isset($p[3])){
                $redirect_url = $p[3];
            }else {
                $redirect_url = null;
            }
            $this->allow($role, $resource, $method);
        }
        foreach($deny as $p){
            $role = $p[0];
            $resource = $p[1];
            $method = $p[2];
            if (isset($p[3])){
                $redirect_url = $p[3];
            }else {
                $redirect_url = null;
            }
            $this->deny($role, $resource, $method);
        }
    }

    private function record(Role $role, $resource, $method, $action){
        $key = "{$role->getRoleName()}-{$action}-{$resource}";
        $this->ptable[$key] = array(
            "role" => $role,
            "resource" => $resource,
            "method" => $method,
            "action" => $action,
        );
    }

}
