<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Roles;

class RolesController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $roles = Roles::All();
            echo json_encode($roles);
        }
    }
}