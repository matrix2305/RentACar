<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Cars;
use App\Model\Comments;
use App\Model\Price;
use Exception;

class CarsController
{
    public function index(){
        $cars = Cars::All();
        echo json_encode($cars);
    }

    public function availableCars() {
        if (Helpers::CheckLoggedIn()){
            $cars = Cars::getAvailable();
            echo json_encode($cars);
        }
    }

    public function store()
    {
        if (Helpers::CheckPermission()) {
            if (isset($_POST['add_car_model']) and isset($_POST['add_car_available']) and isset($_POST['add_car_brand_id']) and isset($_POST['add_car_class_id']) and isset($_POST['add_car_body_id']) and isset($_POST['add_price_24h']) and isset($_POST['add_price_48h']) and isset($_POST['add_price_72h']) and isset($_POST['add_price_7_days']) and isset($_POST['add_price_14_days']) and isset($_FILES['car_image'])) {
                $model = filter_var(trim($_POST['add_car_model']), FILTER_SANITIZE_STRING);
                $available = intval($_POST['add_car_available']);
                $brand_id = intval($_POST['add_car_brand_id']);
                $class_id = intval($_POST['add_car_class_id']);
                $car_body_id = intval($_POST['add_car_body_id']);
                $price_24h = intval($_POST['add_price_24h']);
                $fuels = array_map('intval', explode(',', $_POST['add_car_fuels']));
                $price_48h = intval($_POST['add_price_48h']);
                $price_72h = intval($_POST['add_price_72h']);
                $price_7_days = intval($_POST['add_price_7_days']);
                $price_14_days = intval($_POST['add_price_14_days']);

                if (!empty($fuels) and !empty($model) and !empty($available) and !empty($brand_id) and !empty($class_id) and !empty($car_body_id) and !empty($price_24h) and !empty($price_48h) and !empty($price_72h) and !empty($price_7_days) and !empty($price_14_days) and $_FILES['car_image'] != NULL) {
                    $image = time();
                    ini_set("upload_max_filesize", "20M");
                    $dir = 'asset/img/cars/';
                    $uploaded_img_name = $_FILES['car_image']['name'];
                    $eks = strtolower(pathinfo($uploaded_img_name, PATHINFO_EXTENSION));
                    $image_name = $image .'.'. $eks;
                    if (@!getimagesize($_FILES['car_image']['tmp_name']) or $eks == 'jpg') {
                        $backrequest = [
                            'message' => 'Niste uneli sliku!',
                            'status' => false
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                        exit();
                    }
                    move_uploaded_file($_FILES['car_image']['tmp_name'], $dir . $image_name);


                    try {
                        $car = new Cars();
                        $car->model = $model;
                        $car->available = $available;
                        $car->brands_id = $brand_id;
                        $car->image = $image_name;
                        $car->car_body_id = $car_body_id;
                        $car->class_id = $class_id;
                        $car->rent_reserved = 0;
                        $car->rented = 0;
                        $car->save();
                        $car_id =  $car->lastInsertedId();
                        $car->hasFuels($fuels, $car_id);

                        $price = new Price();
                        $price->cars_id = $car_id;
                        $price->one_day = $price_24h;
                        $price->two_days = $price_48h;
                        $price->three_days = $price_72h;
                        $price->seven_days = $price_7_days;
                        $price->fourteen_days = $price_14_days;
                        $price->save();

                        $backrequest = [
                            'message' => 'Uspešno dodat automobil!',
                            'status' => true
                        ];
                        Helpers::insertLog($backrequest['message']);

                        echo json_encode($backrequest);
                    } catch (Exception $exception) {
                        $backrequest = [
                            'message' => 'Nespešno dodavanje!',
                            'status' => false
                        ];
                        Helpers::insertLog($backrequest['message']);
                        unlink($dir.$image_name);
                        echo json_encode($backrequest);
                    }
                }
            }
        }
    }

    public function destroy()
    {
        if (Helpers::CheckPermission()){
            $request = Helpers::GetJsonRequest();
            if (!empty($request)) {
                $car = Cars::find(['id' => $request->id]);
                $dir = 'public/asset/img/cars/';
                try {
                    Cars::delete(intval($request->id));
                    if(file_exists($dir.$car->image))
                        unlink($dir.$car->image);

                    $backrequest = [
                        'message' => 'Uspešno obrisan automobil!',
                        'status' => true
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                } catch (Exception $exception) {
                    $backrequest = [
                        'message' => 'Nespešno brisanje, postoje iznajmljeni automobili!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);

                    echo json_encode($backrequest);
                }
            }
        }
    }

    public function update() {
        if(isset($_POST['update_car_id']) and isset($_POST['update_car_model']) and isset($_POST['update_car_brand_id']) and isset($_POST['update_car_body_id']) and isset($_POST['update_car_class_id']) and isset($_POST['update_car_available']) and isset($_POST['update_price_24h']) and isset($_POST['update_price_48h']) and isset($_POST['update_price_72h']) and isset($_POST['update_price_7_days']) and isset($_POST['update_price_14_days'])) {
            $id = intval($_POST['update_car_id']);
            $model = filter_var(trim($_POST['update_car_model']), FILTER_SANITIZE_STRING);
            $brand_id = intval($_POST['update_car_brand_id']);
            $car_body_id = intval($_POST['update_car_body_id']);
            $class_id = intval($_POST['update_car_class_id']);
            $available = intval($_POST['update_car_available']);
            $price_24h = intval($_POST['update_price_24h']);
            $price_48h = intval($_POST['update_price_48h']);
            $price_72h = intval($_POST['update_price_72h']);
            $price_7_days = intval($_POST['update_price_72h']);
            $price_14_days = intval($_POST['update_price_72h']);
            $last_image = filter_var(trim($_POST['last_image']), FILTER_SANITIZE_STRING);
            $fuels = array_map('intval', explode(',', $_POST['update_car_fuels']));

            if (isset($_FILES["update_car_image"]) and $_FILES["update_car_image"] != null) {
                $image = time();
                ini_set("upload_max_filesize", "20M");
                $dir = 'asset/img/cars/';
                $uploaded_image_name = $_FILES["update_car_image"]["name"];
                $eks = strtolower(pathinfo($uploaded_image_name, PATHINFO_EXTENSION));
                $image_name = $image . '.' . $eks;
                if (@!getimagesize($_FILES['update_car_image']['tmp_name']) or $eks != 'jpg') {
                    $backrequest = [
                        'message' => 'Niste uneli sliku!',
                        'status' => false
                    ];
                    Helpers::insertLog($backrequest['message']);
                    echo json_encode($backrequest);
                    exit();
                }

                move_uploaded_file($_FILES['update_car_image']['tmp_name'], $dir . $image_name);
                unlink($dir.$last_image);
            }
               try {
                    $car = Cars::find(['id' => $id]);
                    $car->model = $model;
                    $car->available = $available;
                    if (isset($_FILES["update_car_image"]) and $_FILES["update_car_image"] != null) {
                        $car->image = $image_name;
                    }
                    $car->brands_id = $brand_id;
                    $car->class_id = $class_id;
                    $car->car_body_id = $car_body_id;
                    $car->save();
                    $car->hasFuels($fuels, $id);

                    $price = Price::find(['cars_id' => $id]);
                    $price->one_day = $price_24h;
                    $price->two_days = $price_48h;
                    $price->three_days = $price_72h;
                    $price->seven_days = $price_7_days;
                    $price->fourteen_days = $price_14_days;
                    $price->save();


                    $backrequest = [
                        'message' => 'Uspešne izmene automobila!',
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
                    if (isset($_FILES["update_car_image"]) and $_FILES["update_car_image"] != null)
                        unlink($dir.$image_name);
                }

        }
    }
}