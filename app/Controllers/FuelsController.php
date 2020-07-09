<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Fuels;
use App\Logic\Validator;
use Exception;

class FuelsController
{
    public function index(){
        if(Helpers::CheckPermission()){
            $fuels = Fuels::All();
            echo json_encode($fuels);
        }
    }

    public function store(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->fuel) and !empty($request->fuel) ){
                    $fuel_name = filter_var(trim($request->fuel), FILTER_SANITIZE_STRING);
                    try {
                        $fuel = new Fuels();
                        $fuel->fuel = $fuel_name;
                        $fuel->save();

                        $backrequest = [
                            'message' => 'Uspešno dodat pogon!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }catch (Exception $exception){
                        $backrequest = [
                            'message' => 'Pogon već postoji!',
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

    public function update(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)) {
                if (Validator::validateString($request->fuel_name) and !empty($request->fuel_name)) {
                    $fuel_name = filter_var(trim($request->fuel_name), FILTER_SANITIZE_STRING);
                    try {
                        Fuels::update(
                            [
                                'id' => intval($request->id),
                                'fuel' => $fuel_name
                            ]
                        );

                        $backrequest = [
                            'message' => 'Uspešna izmena pogona!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    } catch (Exception $exception) {
                        $backrequest = [
                            'message' => 'Neuspešna izmena pogona!',
                            'status' => false
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }
                } else {
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

    public function destroy(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Fuels::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno obrisan pogon!',
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