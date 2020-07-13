<?php
declare(strict_types = 1);
namespace App\Model;
use ReflectionProperty;
use ReflectionClass;
use App\Logic\Model;
class ReviewsCars extends Model
{
    protected $tableName = 'reviews_cars';

    public $id;

    public $ip_adress;

    public $car_model;

    public $car_brand;

    public $car_id;

    public static function getReviews() : array
    {
        $cars = Cars::All();
        $reviewsCars = array();
        for ($i = 0 ; $i <count($cars); $i++){
            $car = new self();
            $car->car_model = $cars[$i]->model;
            $car->car_brand = $cars[$i]->brand->brand_name;
            $stmt = self::$conn->prepare('SELECT count(*) FROM reviews_cars WHERE car_model = ?');
            $stmt->execute([$cars[$i]->model]);
            $reviews = $stmt->fetchColumn();
            $car->reviews  = $reviews;
            $reviewsCars[] = $car;
        }

        return $reviewsCars;
    }
}