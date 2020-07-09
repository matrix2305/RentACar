<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Logs;

class LogsController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $logs = Logs::All(150);
            echo json_encode($logs);
        }
    }
}