<?php

namespace RBAC;

class Authentication{

    private $ptable = array();

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

        $allow = array_filter($this->ptable, function($i)use($user, $resource, $method, $auth){
            $pattern = sprintf("/^%s$/", str_replace('/', '\/', $i['resource']));
            preg_match($pattern, $resource, $matches);
            return $matches != null
                && ('*' == $i['method'] || $i['method'] == $method)
                && $auth($i['role'], $user)
                && $i['action'] == 'allow';
        });
        
        $deny = array_filter($this->ptable, function($i)use($user, $resource, $method, $auth){
            $pattern = sprintf("/^%s$/", str_replace('/', '\/', $i['resource']));
            preg_match($pattern, $resource, $matches);
            return $matches != null
                && ('*' == $i['method'] || $i['method'] == $method)
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
            $this->allow($role, $resource, $method);
        }
        foreach($deny as $p){
            $role = $p[0];
            $resource = $p[1];
            $method = $p[2];
            $this->deny($role, $resource, $method);
        }
    }

    private function record(Role $role, $resource, $method, $action){
        $key = "{$role->getRoleName()}-{$action}-{$method}-{$resource}";
        $this->ptable[$key] = array(
            "role" => $role,
            "resource" => $resource,
            "method" => $method,
            "action" => $action
        );
    }

}
