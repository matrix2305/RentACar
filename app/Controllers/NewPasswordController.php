<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Logic\Validator;
use App\Model\PasswordRessets;
use App\Model\Users;

class NewPasswordController
{
    public function index(){
        new renderView('LayoutLogin', 'newPassword');
    }

    public function newPassword($token, $email){
        if(isset($_POST['password']) and isset($_POST['cpassword'])) {
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $cpassword = filter_var(trim($_POST['cpassword']), FILTER_SANITIZE_STRING);
            if (!empty($password) and !empty($cpassword) and $password == $cpassword){
                if (Validator::validateString($password)){
                    $checkToken = PasswordRessets::find(['token' => $token, 'email' => $email]);
                    if (!empty($checkToken)){
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        Users::update(['password' => $hash], ['email' => $email]);
                        $_SESSION['success'] = 'Uspešna izmena lozinke!';
                        Helpers::redirect('/login');
                    }
                }else{
                    $_SESSION['error'] = 'Niste uneli ispravnu lozinku!';
                }
            }else{
                $_SESSION['error'] = 'Niste uneli ispravnu lozinku!';
            }
        }
    }
}