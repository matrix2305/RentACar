<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\Validator;
use App\Model\CarBody;
use Exception;

class CarBodiesController
{
    public function index() {
        if (Helpers::CheckPermission()){
            $carBodies = CarBody::All();
            echo json_encode($carBodies);
        }
    }

    public function update() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->car_body_name) and !empty($request->car_body_name)){
                    $car_body_name=filter_var(trim($request->car_body_name), FILTER_SANITIZE_STRING);
                    try {
                        CarBody::update([
                            'id' => $request->id,
                            'car_body_name' => $car_body_name
                        ]);

                        $backrequest = [
                            'message' => 'Uspešno izmenjena karoserija!',
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
                }else{
                    $backrequest = [
                        'message' => 'Niste uneli validano polje!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function store() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->car_body_name) and !empty($request->car_body_name)){
                    $car_body_name=filter_var(trim($request->car_body_name), FILTER_SANITIZE_STRING);
                    try {
                       $carBody = new CarBody();
                       $carBody->car_body_name = $car_body_name;
                       $carBody->save();

                        $backrequest = [
                            'message' => 'Uspešno dodata karoserija!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }catch (Exception $exception){
                        $backrequest = [
                            'message' => 'Karoserija već postoji!',
                            'status' => false
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }
                }else{
                    $backrequest = [
                        'message' => 'Niste uneli validano polje!',
                        'status' => false
                    ];

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function destroy(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    CarBody::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno obrisana karoserija!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno brisanje, postoje vezani automobili!',
                        'status' => false
                    ];

                    echo json_encode($backrequest);
                }
            }
        }
    }
}