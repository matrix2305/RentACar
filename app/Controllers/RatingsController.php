<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Ratings;
use Exception;

class RatingsController
{
    public function index(){
        if (Helpers::CheckPermission()){
            $ratings = Ratings::All();
            echo json_encode($ratings);
        }
    }

    public function ratingsOnHold(){
        if (Helpers::CheckPermission()){
            $ratings = Ratings::find(['allowed' => 0 , 'deleted' => 0]);
            echo json_encode($ratings);
        }
    }

    public function store(){
        $request = Helpers::GetJsonRequest();
        $name=filter_var(trim($request->name), FILTER_SANITIZE_STRING);
        $comment=filter_var(trim($request->message), FILTER_SANITIZE_STRING);

        if(empty($name) and empty($request->rating) and empty($comment)){
            $backrequest = [
                'message' => 'Obavezna polja su prazna!',
                'status' => false
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
            exit();
        }

        try {
            $rating = new Ratings();
            $rating->name = $name;
            $rating->rating = intval($request->rating);
            $rating->comment = $comment;
            $rating->allowed = 0;
            $rating->deleted = 0;
            $rating->save();

            $backrequest = [
                'message' => 'Uspešno ste ocenili našu Rent a car kuću!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }catch (Exception $exception){
            $backrequest = [
                'message' => 'Neuspešno ocenjivanje!',
                'status' => true
            ];
            Helpers::insertLog($backrequest['message']);

            echo json_encode($backrequest);
        }
    }

    public function allowedRatings() {
        $ratings = Ratings::find(['allowed' => 1]);
        echo json_encode($ratings);
    }

    public function allowRating() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Ratings::update(
                        [
                            'id' => intval($request->id),
                            'allowed' => 1
                        ]
                    );
                    $backrequest = [
                        'message' => 'Uspešno odobrena ocena!',
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

    public function disallowRating() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    Ratings::update(
                        [
                            'id' => intval($request->id),
                            'allowed' => 0
                        ]
                    );
                    $backrequest = [
                        'message' => 'Uspešno vraćena ocena!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Neuspešno vraćanje!',
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
                    Ratings::update(
                        [
                            'id' => $request->id,
                            'deleted' => 1
                        ]
                    );

                    $backrequest = [
                        'message' => 'Uspešno obrisan ocena!',
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