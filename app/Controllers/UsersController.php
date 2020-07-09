<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\renderView;
use App\Logic\Validator;
use App\Model\Users;
use Exception;

class UsersController
{
    public function index() {
        if (Helpers::CheckPermission()){
            $users = Users::all();
            echo json_encode($users);
        }
    }

    public function show(){
        if (Helpers::CheckLoggedIn() and $_SESSION['role_id'] == 3){
            $id = $_SESSION['id'];
            $user = Users::find(['id' => $id]);
            echo json_encode($user);
        }else{
            return false;
        }
    }

    public function store() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                $username = filter_var(trim($request->username), FILTER_SANITIZE_STRING);
                $name = filter_var(trim($request->name), FILTER_SANITIZE_STRING);
                $lastname = filter_var(trim($request->lastname), FILTER_SANITIZE_STRING);
                $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
                $password = filter_var(trim($request->password), FILTER_SANITIZE_STRING);
                $cpassword = filter_var(trim($request->cpassword), FILTER_SANITIZE_STRING);
                $adress = filter_var(trim($request->adress), FILTER_SANITIZE_STRING);
                $roles_id=intval($request->role_id);
                if (!empty($username) and !empty($name) and !empty($lastname) and !empty($email) and !empty($password) and !empty($cpassword)){
                    if (Validator::validateString($username) and Validator::validateString($name) and Validator::validateString($lastname) and Validator::validateEmail($email) and Validator::validateString($password) and Validator::validateString($cpassword)){
                        $checkUsername = Users::find(['username' => $username]);
                        $checkEmail = Users::find(['email' => $email]);
                        if (!empty($checkUsername)){
                            $backrequest = [
                                'message' => 'Korisnik sa ovim korisničkim imenom već postji!',
                                'status' => false
                            ];
                            Helpers::insertLog($backrequest['message']);
                            echo json_encode($backrequest);
                            exit();
                        }elseif (!empty($checkEmail)){
                            $backrequest = [
                                'message' => 'Korisnik sa ovom e-poštom već postji!',
                                'status' => false
                            ];
                            Helpers::insertLog($backrequest['message']);
                            echo json_encode($backrequest);
                            exit();
                        }

                        if ($password == $cpassword){
                            try {
                                $user = new Users();
                                $user->username = $username;
                                $user->name = $name;
                                $user->lastname = $lastname;
                                $user->email = $email;
                                $user->adress = $adress;
                                $user->roles_id = $roles_id;
                                $user->password = password_hash($password, PASSWORD_DEFAULT);
                                $user->avatar_name = 'noavatar.jpg';

                                $user->save();

                                $backrequest = [
                                    'message' => 'Uspešno ste se registrovali!',
                                    'status' => true
                                ];
                                Helpers::insertLog($backrequest['message']);
                                echo json_encode($backrequest);
                            }catch (Exception $exception){
                                $backrequest = [
                                    'message' => 'Neuspešna registracija!',
                                    'status' => true
                                ];
                                Helpers::insertLog($backrequest['message']);
                                echo json_encode($backrequest);
                            }
                        }else{
                            $backrequest = [
                                'message' => 'Nepoklapajuće lozinke!',
                                'status' => false
                            ];
                            Helpers::insertLog($backrequest['message']);

                            echo json_encode($backrequest);
                        }
                    }else{
                        $backrequest = [
                            'message' => 'Uneli ste nevalidne karaktere!',
                            'status' => false
                        ];
                        Helpers::insertLog($backrequest['message']);
                        echo json_encode($backrequest);
                    }
                }else{
                    $backrequest = [
                        'message' => 'Uneli ste prazna polja!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);
                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function update(){
        if (isset($_POST['update_user_username']) and isset($_POST['update_user_email']) and isset($_POST['update_user_name']) and isset($_POST['update_user_lastname']) and isset($_POST['update_user_password']) and isset($_POST['update_user_cpassword']) and isset($_POST['update_user_phone_num'])) {
            if (!empty($_POST['update_user_id'])){
                $id = intval($_POST['update_user_id']);
            }else{
                $id = $_SESSION['id'];
            }
            $username = filter_var(trim($_POST['update_user_username']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['update_user_email']), FILTER_SANITIZE_EMAIL);
            $name = filter_var(trim($_POST['update_user_name']), FILTER_SANITIZE_STRING);
            $lastname = filter_var(trim($_POST['update_user_lastname']), FILTER_SANITIZE_STRING);
            $adress = filter_var(trim($_POST['update_user_adress']), FILTER_SANITIZE_STRING);
            $password = trim($_POST['update_user_password']);
            $cpassword = trim($_POST['update_user_cpassword']);
            $phone_num = filter_var(trim($_POST['update_user_phone_num']), FILTER_SANITIZE_STRING);
            if (!empty($_POST['update_user_role_id'])){
                $role_id = intval($_POST['update_user_role_id']);
            }elseif(Helpers::CheckPermission()){
                $role_id = $_SESSION['role_id'];
            }else {
                $role_id = 3;
            }

            if(empty($username) and  empty($email)){
                $backrequest = [
                    'message' => 'Obavezna polja su prazna!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif($password != $cpassword or strlen($password) < 8){
                $backrequest = [
                    'message' => 'Lozike nisu validne!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);
                echo json_encode($backrequest);
                exit();
            }

            try {
                $user = Users::find(['id' => $id]);
                if (!empty($_FILES['update_user_avatar'])){
                    $dir = 'asset/img/avatars/';
                    if ($user->avatar_name != 'noavatar.jpg'){
                        unlink($dir.$user->avatar_name);
                    }
                    $name = time();
                    $eks = strtolower(pathinfo($_FILES['update_user_avatar']['name'], PATHINFO_EXTENSION));
                    $avatar_name = $name.'.'.$eks;
                    if (getimagesize($_FILES['update_user_avatar']['tmp_name']) or $eks == 'jpg'){
                        move_uploaded_file($_FILES['update_user_avatar']['tmp_name'], $dir.$avatar_name);
                    }else{
                        $backrequest = [
                            'message' => 'Niste uneli sliku!',
                            'status' => false
                        ];

                        echo json_encode($backrequest);
                        exit();
                    }
                }else{
                    $avatar_name = $user->avatar_name;
                }


                $user->username = $username;
                $user->email = $email;
                if (!empty($password)){
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                }

                $user->name = $name;
                $user->lastname = $lastname;
                $user->roles_id = $role_id;
                $user->adress = $adress;
                $user->phone_number = $phone_num;
                $user->avatar_name = $avatar_name;
                $user->save();

                $backrequest = [
                    'message' => 'Uspešne izmene!',
                    'status' => true
                ];

                echo json_encode($backrequest);
            }catch (Exception $exception){
                $backrequest = [
                    'message' => 'Neuspešne izmene!',
                    'status' => false
                ];

                echo json_encode($backrequest);
            }
        }
    }

    public function destroy() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Users::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno obrisan korisnik!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);
                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Došlo je do problema pri brisanju!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);
                    echo json_encode($backrequest);
                }
            }
        }
    }
}