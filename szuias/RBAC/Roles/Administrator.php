<?php

namespace RBAC\Roles;

class Administrator extends NormalUser {

    protected $roleName = "Administrator";
    protected $parentName = "NormalUser";

    public function getParentName() {
        return $this->parentName;
    }

    public function getRoleName() {
        return $this->roleName;        
    }

    public function authenticate(\Model\User $user=null) {
        return $user != null && $user->isAdmin();
    }
}
