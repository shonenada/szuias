<?php

namespace RBAC;

interface Role{
    public function getRoleName();
    public function getParentName();
    public function authenticate(\Model\User $user=null);
}
