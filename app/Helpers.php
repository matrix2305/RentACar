<?php
declare(strict_types = 1);
namespace App;

use App\Model\Activity;
use App\Model\Logs;
use App\Model\Reviews;
use App\Model\ReviewsCars;
use App\Model\Users;
use Exception;

class Helpers {

    /**
     * Set sessions for log in user
     * @param $name
     * @param $id
     * @param $role_id
     */
    public static function setSession($id, $name, $role_id) : void
    {
        $_SESSION['id']=$id;
        $_SESSION['username']=$name;
        $_SESSION['role_id']=$role_id;
    }


    /**
     * Function for redirect page
     * @param $route
     */

    public static function redirect($route) : void
    {
        header("location: {$route}");
    }


    /*
     * Function for get input post request data with JSON
     */

    public static function GetJsonRequest()
    {
        $postdata = file_get_contents("php://input");
        if($postdata) {
            return json_decode($postdata);
        }else{
            return false;
        }
    }

    public static function CheckLoggedIn() : bool
    {
        if (isset($_SESSION['id']) and isset($_SESSION['username']) and isset($_SESSION['role_id'])){
            return true;
        }elseif(isset($_COOKIE['id']) and isset($_COOKIE['username'])){
            $user = Users::find(['id' => $_COOKIE['id']]);
            Helpers::setSession($user->id, $user->username, $user->roles_id);
            return true;
        }else{
            return false;
        }
    }

    public static function logout() : void
    {
        if(isset($_GET['logout'])){
            session_unset();
            session_destroy();
            setcookie('id', '', time() - 3600, "/");
            setcookie('username', '', time() - 3600, "/");
            self::redirect('/login');
        }
    }

    public static function directLogout() : void
    {
        session_unset();
        session_destroy();
        setcookie('id', '', time() - 3600, "/");
        setcookie('username', '', time() - 3600, "/");
        self::redirect('/');
    }

    public static function rememberLogin($id, $username) : void
    {
        setcookie("id", $id, time()+86400, "/");
        setcookie("username", $username, time()+86400, "/");
    }

    public static function CheckPermission() : bool
    {
        if (isset($_SESSION['role_id'])){
            if($_SESSION['role_id'] == 1 or $_SESSION['role_id'] == 2){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public static function storeReviewCar() {
        $request = self::GetJsonRequest();
        if (!empty($request)){
            $car_id = intval($request->car_id);
            $car_model = filter_var(trim($request->car_model), FILTER_SANITIZE_STRING);
            $car_brand = filter_var(trim($request->car_brand), FILTER_SANITIZE_STRING);
            $ip_adress = $_SERVER['REMOTE_ADDR'];
            $checkReview = ReviewsCars::find(
                [
                    'ip_adress' => $ip_adress,
                    'car_id' => $car_id,
                ]
            );
            if (empty($checkReview)){
                try {
                    $review = new ReviewsCars();
                    $review->car_id = $car_id;
                    $review->car_brand = $car_brand;
                    $review->car_model = $car_model;
                    $review->ip_adress = $ip_adress;
                    $review->save();
                }catch (Exception $exception){
                    echo $exception->getMessage();
                }
            }
        }
    }

    public static function storeActivity() : void
    {
        $url_path = $_SERVER['REQUEST_URI'];
        $ip_adress = $_SERVER['REMOTE_ADDR'];
        $activity = new Activity();
        $activity->ip_adress = $ip_adress;
        $activity->path = $url_path;
        $activity->save();
    }

    public static function storeReview() : void
    {
        $url_path = $_SERVER['REQUEST_URI'];
        $ip_adress = $_SERVER['REMOTE_ADDR'];
        $activity = Activity::find([
            'ip_adress' => $ip_adress,
            'path' => $url_path
        ]);
        if (empty($activity)){
            $review = Reviews::find([
                'path' => $url_path
            ]);
            if (empty($review)){
                $review = new Reviews();
                $review->path = $url_path;
                $review->reviews = 1;
                $review->save();
            }else{
                $review->reviews =  $review->reviews + 1;
                $review->save();
            }
        }
    }

    public static function insertLog(string $input) : void
    {
        $ip_adress = $_SERVER['REMOTE_ADDR'];
        if(isset($_SESSION['username'])){
            $user = " Korisnik: {$_SESSION['username']} ";
        }else{
            $user = '';
        }
        $text = date('m/d/Y H:i:s', time()). ' - ' . $input . $user. ' IP:' . $ip_adress;
        $log = new Logs();
        $log->log = $text;
        $log->save();
    }
}