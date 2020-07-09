<?php
namespace App\Controllers;

use App\Helpers;
use App\Logic\Validator;
use App\Model\Cars;
use App\Model\MonitoringCars;
use App\Model\RentCars;
use DateTime;
use Exception;

class RentCarsController
{
    public function index() {
        if(Helpers::CheckPermission()){
            $rented_cars = RentCars::All();
            if (!is_array($rented_cars)){
                $rented_cars = [$rented_cars];
            }
            echo json_encode($rented_cars);
        }
    }

    public function userCars(){
        if (Helpers::CheckLoggedIn()){
            $id = $_SESSION['id'];
            $rented_cars = array();
            $r_cars_id = RentCars::getRentedCarsIdUser($id);
            foreach($r_cars_id as $r_id){
                $car = RentCars::find(['deleted' => 0, 'id' => $r_id['rented_cars_id']]);
                if (!empty($car)){
                    $rented_cars[] = $car;
                }
            }
            echo json_encode($rented_cars);
        }
    }

    public function carsDeleted(){
        if(Helpers::CheckPermission()){
            $rented_cars = RentCars::find(['deleted' => 1]);
            if (!is_array($rented_cars)){
                $rented_cars = [$rented_cars];
            }
            if (!is_array($rented_cars)){
                $rented_cars = [$rented_cars];
            }
            echo json_encode($rented_cars);
        }
    }

    public function carsOnHold() {
        if(Helpers::CheckPermission()){
            $rented_cars = RentCars::find(['allowed' => 0, 'deleted' => 0]);
            if (!is_array($rented_cars)){
                $rented_cars = [$rented_cars];
            }
            echo json_encode($rented_cars);
        }
    }

    public function carsAllowed() {
        if(Helpers::CheckPermission()){
            $rented_cars = RentCars::find(['allowed' => 1, 'deleted' => 0]);
            if (!is_array($rented_cars)){
                $rented_cars = [$rented_cars];
            }
            echo json_encode($rented_cars);
        }
    }

    public function update(){
        $request = Helpers::GetJsonRequest();
        if (!empty($request)){
            $id = intval($request->rent_car_id);
            $car_id = intval($request->car_id);
            $name = filter_var(trim($request->name), FILTER_SANITIZE_STRING);
            $lastname = filter_var(trim($request->lastname), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
            $phone_number = filter_var(trim($request->mobile_phone), FILTER_SANITIZE_STRING);
            $JMBG = intval($request->JMBG);
            $rent_time_end = $request->rent_time_end;
            $rent_time_start = $request->rent_time_start;
            $price = intval($request->price);
            $adress = filter_var(trim($request->adress), FILTER_SANITIZE_STRING);

            $car = Cars::find(['id' => $car_id]);

            if(empty($car_id) or empty($name) or empty($lastname) or empty($email) or empty($phone_number) or empty($JMBG) or empty($price) and empty($adress)) {
                $backrequest = [
                    'message' => 'Neka od obaveznih polja je prazno!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif($car->available == $car->rent_reserved){
                $backrequest = [
                    'message' => 'Nije moguće iznajmiti automobil - svi automobili pod ovim modelom su rezervisani!!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif ($car->available == 0){
                $backrequest = [
                    'message' => 'Ovaj automobil trenutno nije na stanju!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif (Validator::validateEmail($email) == false){
                $backrequest = [
                    'message' => 'E-pošta nije validna!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }

            function backrequest(){
                $backrequest = [
                    'message' => 'Molimo unesite ispravan datum!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }

            $period = $rent_time_end / 1000 - $rent_time_start / 1000;
            $days = $period / 60 / 60 / 24;


            if ($days == 1){
                if ($price != $car->price->one_day){
                    backrequest();
                }
            }elseif ($days == 2){
                if ($price != $car->price->two_days){
                    backrequest();
                }
            }elseif ($days == 3){
                if ($price != $car->price->three_days){
                    backrequest();
                }
            }elseif ($days == 7){
                if ($price != $car->price->seven_days){
                    backrequest();
                }
            }elseif ($days == 14){
                if ($price != $car->price->fourteen_days){
                    backrequest();
                }
            }else{
                $check = intval($car->price->one_day * $days / 1.5);
                if ($check != $price){
                    backrequest();
                }
            }

            try {
                $rentcar = RentCars::find(['id' => $id]);
                $rentcar->name = $name;
                $rentcar->lastname = $lastname;
                $rentcar->email = $email;
                $rentcar->phone_number = $phone_number;
                $rentcar->JMBG = $JMBG;
                $rentcar->adress = ($adress == null)? null : $adress;
                $rentcar->allowed = 0;
                $rentcar->rented_time = null;
                $rentcar->period_of_rent = $days;
                $rentcar->start_renting_date = date('d-m-Y', $rent_time_start / 1000);
                $rentcar->end_renting_date = date('D M d Y H:i:s O', $rent_time_end / 1000);
                $rentcar->price = $price;
                if ($rentcar->cars_id != $car_id){
                    $car = Cars::find(['id' => $rentcar->cars_id]);
                    $car->available = $car->available + 1;
                    $car->rent_reserved = $car->rent_reserved - 1;
                    $car->save();
                    $rentcar->cars_id = $car_id;
                    $car = Cars::find(['id' => $car_id]);
                    $car->available = $car->available - 1;
                    $car->rent_reserved = $car->rent_reserved + 1;
                    $car->save();
                }
                $rentcar->deleted = 0;
                $rentcar->save();

                $message = 'Uspešno izmenjen rentiran automobil!';

                $backrequest = [
                    'message' => $message,
                    'status' => true
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
           }catch (Exception $exception) {
                $backrequest = [
                    'message' => 'Nije moguća izmena automobila!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
            }

        }
    }

    public function store(){
        $request = Helpers::GetJsonRequest();
        if(!empty($request)){
            $car_id = intval($request->id);
            $name = filter_var(trim($request->name), FILTER_SANITIZE_STRING);
            $lastname = filter_var(trim($request->lastname), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
            $phone_number = filter_var(trim($request->mobile_phone), FILTER_SANITIZE_STRING);
            $JMBG = intval($request->JMBG);
            $rent_time_end = $request->rent_time_end;
            $rent_time_start = $request->rent_time_start;
            $price = intval($request->price);
            $adress = filter_var(trim($request->adress), FILTER_SANITIZE_STRING);

            $car = Cars::find(['id' => $car_id]);

            if(empty($car_id) or empty($name) or empty($lastname) or empty($email) or empty($phone_number) or empty($JMBG) or empty($price) and empty($adress)) {
                $backrequest = [
                    'message' => 'Neka od obaveznih polja je prazno!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif($car->available == $car->rent_reserved){
                $backrequest = [
                    'message' => 'Nije moguće iznajmiti automobil - svi automobili pod ovim modelom su rezervisani!!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }elseif ($car->available == 0){
                $backrequest = [
                    'message' => 'Ovaj automobil trenutno nije na stanju!',
                    'status' => false
                ];

                echo json_encode($backrequest);
                exit();
            }elseif (Validator::validateEmail($email) == false){
                $backrequest = [
                    'message' => 'E-pošta nije validna!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }

            function backrequest(){
                $backrequest = [
                    'message' => 'Cena nije u redu!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
                exit();
            }

            $period = $rent_time_end / 1000 - $rent_time_start / 1000;
            $days = $period / 60 / 60 / 24;

            if ($days == 1){
                if ($price != $car->price->one_day){
                    backrequest();
                }
            }elseif ($days == 2){
                if ($price != $car->price->two_days){
                    backrequest();
                }
            }elseif ($days == 3){
                if ($price != $car->price->three_days){
                    backrequest();
                }
            }elseif ($days == 7){
                if ($price != $car->price->seven_days){
                    backrequest();
                }
            }elseif ($days == 14){
                if ($price != $car->price->fourteen_days){
                    backrequest();
                }
            }else{
                $check = intval($car->price->one_day * $days / 1.5);
                if ($check != $price){
                    backrequest();
                }
            }

           try {
               $rentcar = new RentCars();
               $rentcar->name = $name;
               $rentcar->lastname = $lastname;
               $rentcar->email = $email;
               $rentcar->phone_number = $phone_number;
               $rentcar->JMBG = $JMBG;
               $rentcar->adress = ($adress == null) ? null : $adress;
               $rentcar->allowed = 0;
               $rentcar->rented_time = null;
               $rentcar->period_of_rent = $days;
               $rentcar->start_renting_date = date('d-m-Y', $rent_time_start / 1000);
               $rentcar->end_renting_date = date('D M d Y H:i:s O', $rent_time_end / 1000);
               $rentcar->price = $price;
               $rentcar->cars_id = $car_id;
               $rentcar->deleted = 0;
               $rentcar->save();

               if (Helpers::CheckLoggedIn() and $_SESSION['role_id'] == 3) {
                   $user_id = $_SESSION['id'];
                   $rentcar->addUserRenter(
                       [
                           'user_id' => $user_id,
                           'rent_car_id' => $rentcar->lastInsertedId(),
                       ]
                   );
               }

               $car = Cars::find(['id' => $car_id]);
               $car->rent_reserved = $car->rent_reserved + 1;
               $car->available = $car->available - 1;
               $car->save();


               if ($adress == null) {
                   $message = 'Uspešno iznamljen automobil, možete preuzeti automobil na našoj adresi!';
               } else {
                   $message = 'Uspešno iznamljen autobmobil, u roku od sat vremena ćemo isporučiti automobil na adresi!';
               }

               $subject = 'Iznamljivanje automobila - Rent a car Speed';
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
           }catch (Exception $exception) {
                $backrequest = [
                    'message' => 'Nije moguće iznajmljivanje automobila!',
                    'status' => false
                ];
               Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
            }
        }
    }

    public function allow(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            try {
                $rentedTime = date('m/d/Y H:i:s', time());
                $rentedCar = RentCars::find(['id' => $request->id]);
                $rentedCar->allowed = 1;
                $rentedCar->rented_time = $rentedTime;
                $rentedCar->save();

                $car = Cars::find(['id' => $rentedCar->cars_id]);
                $car->rented = $car->rented + 1;
                $car->save();

                $backrequest = [
                    'message' => 'Uspešno odobreno rentiranje!',
                    'status' => true
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);

            }catch (Exception $exception){
                $backrequest = [
                    'message' => 'Nije moguće odobravanje rentiranja automobila!',
                    'status' => false
                ];
                Helpers::insertLog($backrequest['message']);

                echo json_encode($backrequest);
            }
        }
    }


    public function disallow() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    $rentedcar = RentCars::find(['id' => $request->id]);
                    $rentedcar->allowed = 0;
                    $rentedcar->rented_time = null;
                    $rentedcar->save();

                    $car = Cars::find(['id' => $rentedcar->cars_id]);
                    $car->rented = $car->rented - 1;
                    $car->save();

                    $backrequest = [
                        'message' => 'Uspešno vraćen automobil na listu čekanja!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);

                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Nije moguće vracanje rentiranja automobila!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function destroy() {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                try {
                    RentCars::delete(intval($request->id));

                    $backrequest = [
                        'message' => 'Uspešno vraćen u vozni park!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }catch (Exception $exception){
                    $backrequest = [
                        'message' => 'Nije moguće vracanje automobila!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function getPositionsofCar(){
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)){
                $positions = MonitoringCars::find(['rented_cars_id' => $request->id]);
                if (!is_array($positions)){
                    $positions = [$positions];
                }
                echo json_encode($positions);
            }
        }
    }
}