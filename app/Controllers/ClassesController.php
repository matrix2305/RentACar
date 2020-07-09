<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Classes;
use App\Logic\Validator;
use Exception;

class ClassesController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $classes = Classes::All();
            echo json_encode($classes);
        }
    }

    public function store(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->class_name) and !empty($request->class_name) and Validator::validateString($request->class_color) and !empty($request->class_color)){
                    $class_name=filter_var(trim($request->class_name), FILTER_SANITIZE_STRING);
                    $class_color = filter_var(trim($request->class_color), FILTER_SANITIZE_STRING);
                    try {
                        $class = new Classes();
                        $class->class_name = $class_name;
                        $class->class_color = $class_color;
                        $class->save();

                        $backrequest = [
                            'message' => 'Uspešno dodata klasa!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }catch (Exception $exception){
                        $backrequest = [
                            'message' => 'Klasa već postoji!',
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

    public function update() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->class_name) and !empty($request->class_name) and Validator::validateString($request->class_color) and !empty($request->class_color)){
                    $class_name=filter_var(trim($request->class_name), FILTER_SANITIZE_STRING);
                    $class_color = filter_var(trim($request->class_color), FILTER_SANITIZE_STRING);
                    try {
                        Classes::update(
                            [
                                'id' => intval($request->id),
                                'class_color' => $class_color,
                                'class_name' => $class_name
                            ]
                        );

                        $backrequest = [
                            'message' => 'Uspešna izmena klase!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    }catch (Exception $exception){
                        $backrequest = [
                            'message' => 'Neuspešna izmena klase!',
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

    public function destroy(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Classes::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno obrisana klasa!',
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