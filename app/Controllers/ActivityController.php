<?php
namespace App\Controllers;


use App\Helpers;
use App\Model\Activity;

class ActivityController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $activity = Activity::All(150);
            echo json_encode($activity);
        }
    }
}