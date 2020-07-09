<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\Validator;
use App\Model\ContactInfo;
use Exception;

class ContactInfoController
{
    public function index(){
        $contactInfo = ContactInfo::All();
        echo json_encode($contactInfo);
    }

    public function update(){

        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            $name = filter_var(trim($request->name), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
            $mobile_phone = filter_var(trim($request->mobile_phone), FILTER_SANITIZE_STRING);
            $fix_phone = filter_var(trim($request->fix_phone), FILTER_SANITIZE_STRING);
            $adress = filter_var(trim($request->adress), FILTER_SANITIZE_STRING);
            $fb_url = $request->fburl;
            $inst_url = $request->insturl;
            if(empty($name) and empty($email) and empty($mobile_phone) and empty($fix_phone) and empty($adress)) {
                $backrequest = [
                    'message' => 'Uneli ste prazna polja!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }

            try {
                ContactInfo::update(
                    [
                        'id' => 1,
                        'name' => $name,
                        'email' => $email,
                        'mobile_number' => $mobile_phone,
                        'fix_number' => $fix_phone,
                        'adress' => $adress,
                        'facebook_url' => (!empty($fb_url))? $fb_url : null,
                        'instagram_url' => (!empty($inst_url))? $inst_url : null
                    ]
                );

                $backrequest = [
                    'message' => 'Uspešne izmene!',
                    'status' => true
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
            }catch (Exception $exception){
                $backrequest = [
                    'message' => 'Neuspešne izmene!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
            }
        }
    }
}