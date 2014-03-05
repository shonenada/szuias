<?php

namespace RBAC\Roles;
use RBAC\Role;

class EveryOne implements Role {

    protected $roleName = "EveryOne";
    protected $parentName = null;

    public function getRoleName() {
        return $this->roleName;
    }

    public function getParentName() {
        return $this->parentName;
    }

    public function authenticate(\Model\User $user=null) {
        return true;
    }

}
