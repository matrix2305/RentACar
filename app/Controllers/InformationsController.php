<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Informations;
use Exception;

class InformationsController
{
    public function index(){
        $informations = Informations::find(['id' => 1]);
        echo json_encode($informations);
    }

    public function update(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            $informations = filter_var(trim($request->info), FILTER_SANITIZE_STRING);
            if(!empty($informations)) {
                try {
                    Informations::update(
                        [
                            'id' => 1,
                            'informations' => $informations
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
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }
}