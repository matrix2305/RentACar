<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\Validator;
use App\Model\Messages;
use Exception;

class MessagesController
{
    public function index() {
        if (Helpers::CheckPermission()){
            $messages = Messages::find(['deleted' => 0]);
            if (is_array($messages)){
                echo json_encode($messages);
            }else{
                echo json_encode([$messages]);
            }
        }
    }

    public function store(){
        $request = Helpers::GetJsonRequest();
        $name=filter_var(trim($request->name), FILTER_SANITIZE_STRING);
        $lastname=filter_var(trim($request->lastname), FILTER_SANITIZE_STRING);
        $email=filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
        $messageinput=filter_var(trim($request->message), FILTER_SANITIZE_STRING);

        if(empty($name) or empty($lastname) or empty($email) or empty($messageinput)){
            $backrequest = [
                'message' => 'Uneli ste prazna polja!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
            exit();
        }elseif(!Validator::validateEmail($email)){
            $backrequest = [
                'message' => 'Uneli ste nevalidnu e-poštu!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
            exit();
        }

        try {
            $message = new Messages();
            $message->name = $name;
            $message->lastname = $lastname;
            $message->email = $email;
            $message->deleted = 0;
            $message->message = $messageinput;
            $message->save();

            $message = 'Uspešno ste nas kontaktirali, odgovor će te dobiti u što kraćem roku!';
            $subject = 'Kontakt korisnika - Rent a car Speed';
            $to = $email;
            $message = wordwrap($message);
            $headers = 'From: info@rentacar-speed.rs';
            mail($to, $subject, $message, $headers);

            $backrequest = [
                'message' => $message,
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }catch (Exception $exception){
            $backrequest = [
                'message' => 'Neuspešno kontaktiranje!',
                'status' => false
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }
    }

    public function reply() {
        $request = Helpers::GetJsonRequest();
        $message = filter_var(trim($request->message), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);

        if(empty($message)){
            $backrequest = [
                'message' => 'Uneli ste praznu poruku!',
                'status' => false
            ];

            echo json_encode($backrequest);
            exit();
        }

        try {
            $subject = 'Odgovor na poruku - Rent a car Speed';
            $to = $email;
            $message = wordwrap($message);
            $headers = 'From: info@rentacar-speed.rs';
            mail($to, $subject, $message, $headers);
            $backrequest = [
                'message' => 'Uspešno poslat odgovor!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }catch (Exception $exception){
            $backrequest = [
                'message' => 'Neuspešno poslat odgovor!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }
    }

    public function destroy(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Messages::update(
                        [
                            'id' => intval($request->id),
                            'deleted' => 1
                        ]
                    );

                    $backrequest = [
                        'message' => 'Uspešno obrisana poruka!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno brisanje!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }

}