<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\User;

class AuthUser
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function isAuthorized()
    {

        if ($this->user->activity != 0) {
            return true;
        }
        return false;
    }

    public function Role()
    {
        $u = new User();
        $user = $u->find($this->user->id);
        $role = $user->role;

        return $role;
    }

    public function isAdmin()
    {

        if ($this->user->activity != 0 && $this->user->role_id == 1) {
            return true;
        }
        return false;
    }
}
