<?php
namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Model\Users;

class UserController
{
    public function index() {
        if ($_SESSION['role_id'] != 3){
            Helpers::redirect('/');
        }
        if (isset($_GET['logout'])){
            Helpers::logout();
        }
        $user = Users::find(['id' => $_SESSION['id']]);
        $view = new renderView('layoutUserPanel', 'user');
        $view->assignVariable('user', $user);
    }
}