<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Model\ContactInfo;
use App\Model\Users;

class PortalController
{
    public function index() {
        $socnet = ContactInfo::find(['id' => 1]);
        if (isset($_GET['logout'])){
            Helpers::logout();
        }

        $view = new renderView('layoutPortal', 'portal');
        $view->assignVariable('socnet', $socnet);
        if (Helpers::CheckLoggedIn()){
            $user = Users::find(['id' => $_SESSION['id']]);
            $view->assignVariable('user', $user);
        }
    }
}