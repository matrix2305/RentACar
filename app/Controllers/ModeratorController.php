<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Model\Users;

class ModeratorController
{
    public function index(){
        if ($_SESSION['role_id'] != 2){
            Helpers::redirect('/');
        }
        if (isset($_GET['logout'])){
            Helpers::logout();
        }
        if (!Helpers::CheckLoggedIn()){
            Helpers::redirect('login');
        }
        $user = Users::find(['id' => $_SESSION['id']]);
        $view = new renderView('layoutPanel', 'moderator');
        $view->assignVariable('user', $user);
    }
}