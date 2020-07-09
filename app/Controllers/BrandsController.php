<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\Validator;
use App\Model\Brands;
use Exception;

class BrandsController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $brands = Brands::All();
            echo json_encode($brands);
        }
    }

    public function store(){
       if (Helpers::CheckPermission()){
           $request = Helpers::GetJsonRequest();
           if (!empty($request)){
               if (!empty($request->brand_name)) {
                   $brand_name=filter_var(trim($request->brand_name), FILTER_SANITIZE_STRING);
                   try {
                       $brand = new Brands();
                       $brand->brand_name = $brand_name;
                       $brand->save();
                       $backrequest = [
                           'message' => 'Uspešno dodat brend!',
                           'status' => true
                       ];
                       Helpers::insertLog($backrequest['message']);
                       echo json_encode($backrequest);
                   }catch (Exception $exception){
                       $backrequest = [
                           'message' => 'Brend već postoji!',
                           'status' => false
                       ];
                       Helpers::insertLog($backrequest['message']);

                       echo json_encode($backrequest);
                   }
               }else{
                   $backrequest = [
                       'message' => 'Uneli ste prazno polje!',
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
                    Brands::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno obrisan brend!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno brisanje!',
                        'status' => false
                    ];

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function update() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                if (Validator::validateString($request->brand_name) and !empty($request->brand_name)) {
                    $brand_name=filter_var(trim($request->brand_name), FILTER_SANITIZE_STRING);
                    try {
                        Brands::update([
                            'id' => $request->id,
                            'brand_name' => $brand_name
                        ]);

                        $backrequest = [
                            'message' => 'Uspešno izmenjen brend!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    } catch (Exception $exception) {
                        $backrequest = [
                            'message' => 'Brend već postoji!',
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
}