<?php


namespace App\Controllers;


use App\Helpers;
use App\Logic\renderView;
use App\Model\PasswordRessets;
use App\Model\Users;

class RessetPasswordController
{
    public function index(){
        new renderView('LayoutLogin', 'ressetPassword');
    }

    public function ressetPassword(){
        if (isset($_POST['resset_mail']) and !empty($_POST['resset_mail'])){
            $email = $_POST['resset_mail'];
            $checkUser = Users::find(['email' => $email]);
            if (!empty($checkUser)){
                $token = bin2hex(random_bytes(50));
                PasswordRessets::create(['email' => $email, 'token' => $token]);
                $to=$email;
                $subject= 'Resetovanje lozinke korisnika administracionog panela Rent a car Speed';
                // Change href on add site on server
                $message= "Zdravo, kliknite za resetovanje vaše lozinke <a href='https://www.rentacar-speed.rs/newpassword/{$token}/{$email}'>ovde</a>. <br> Vaša Rent a car aplikacija! ";
                $message=wordwrap($message, 70);
                $headers='From: info@rentacar-speed.rs';
                mail($to, $subject, $message, $headers);
                $_SESSION['success'] = 'Uspešno poslat kod za resetovanje lozinke na vašu e-poštu!';
                Helpers::redirect('/login');
            }else{
                $_SESSION['error'] = 'Korisnik sa ovom e-poštom ne postoji!';
            }
        }
    }
}