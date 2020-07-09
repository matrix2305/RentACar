<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Model\Users;

class AdminController
{
    public function index() {
        if (!Helpers::CheckLoggedIn()){
            Helpers::redirect('login');
        }
        if ($_SESSION['role_id'] != 1){
            Helpers::redirect('/');
        }
        if (isset($_GET['logout'])){
            Helpers::logout();
        }

        $user = Users::find(['id' => $_SESSION['id']]);
        $view = new renderView('layoutPanel', 'admin');
        $view->assignVariable('user', $user);
    }
}