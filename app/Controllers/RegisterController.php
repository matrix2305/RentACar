<?php


namespace App\Controllers;


use App\Logic\renderView;
use App\Logic\Validator;
use App\Helpers;
use App\Model\Users;

class RegisterController
{
    public function index(){
        new renderView('layoutLogin', 'register');
    }

    public function register(){
        if (isset($_POST['username']) and isset($_POST['name']) and isset($_POST['lastname']) and isset($_POST['adress']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['cpassword'])){
            $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_STRING);
            $adress = filter_var(trim($_POST['adress']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $cpassword = filter_var(trim($_POST['cpassword']), FILTER_SANITIZE_STRING);


            if (!empty($username) and !empty($name) and !empty($lastname) and !empty($adress) and !empty($email) and !empty($password) and !empty($cpassword)){
                if (Validator::validateString($username) and Validator::validateString($name) and Validator::validateString($lastname) and Validator::validateEmail($email) and Validator::validateString($password) and Validator::validateString($cpassword)){
                    $checkUsername = Users::find(['username' => $username]);
                    $checkEmail = Users::find(['email' => $email]);
                    if (!empty($checkUsername)){
                        $_SESSION['error'] = 'Korisnik sa ovim korisničkim imenom već postji!';
                        Helpers::redirect('register');
                        exit();
                    }elseif (!empty($checkEmail)){
                        $_SESSION['error'] = 'Korisnik sa ovom e-poštom već postji!';
                        Helpers::redirect('register');
                        exit();
                    }

                    if ($password == $cpassword){
                        $user = new Users();
                        $user->username = $username;
                        $user->name = $name;
                        $user->lastname = $lastname;
                        $user->adress = $adress;
                        $user->email = $email;
                        $user->roles_id = 3;
                        $user->password = password_hash($password, PASSWORD_DEFAULT);
                        $user->save();
                        $_SESSION['success'] = 'Uspešno ste se registrovali!';
                        Helpers::redirect('register');
                    }else{
                        $_SESSION['error'] = 'Nepoklapajuće lozinke!';
                        Helpers::redirect('register');
                    }
                }else{
                    $_SESSION['error'] = 'Uneli ste nevalidne karaktere!';
                    Helpers::redirect('register');
                }
            }else{
                $_SESSION['error'] = 'Uneli ste prazna polja!';
                Helpers::redirect('register');
            }
        }
    }
}