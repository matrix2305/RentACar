<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;
use PDOException;
use PDO;
use ReflectionProperty;
use ReflectionClass;

class Cars extends Model
{
    public $id;

    public $model;

    public $available;

    public $image;

    public $rent_reserved;

    public $rented;

    public $brands_id;

    public $class_id;

    public $car_body_id;

    public $created_at;

    public $updated_at;

    public $price;

    public $fuels = array();

    public $brand;

    public $carBody;

    public $class;


    public static function delete(int $id)
    {
        $class = new self();
        self::$conn->beginTransaction();
        try {
            $sql = self::$conn->prepare('DELETE FROM fuels_cars WHERE cars_id = ?');
            $sql->execute(array($id));
            $sql = self::$conn->prepare('DELETE FROM price WHERE cars_id = ?');
            $sql->execute(array($id));
            $sql = self::$conn->prepare('DELETE FROM cars WHERE id = ?');
            $sql->execute(array($id));
            self::$conn->commit();
        }catch (PDOException $exception){
            self::$conn->rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public static function update(array $data, $where = NULL)
    {
        $class = new self();
        self::$conn->beginTransaction();
        try {
            $sql = self::$conn->prepare('UPDATE price SET one_day = ?, two_days = ?, three_days = ?, seven_days = ?, fourteen_days = ? WHERE cars_id = ?');
            $sql->execute(array($data['price_24h'], $data['price_48h'], $data['price_72h'], $data['price_7_days'], $data['price_14_days'], $data['id']));
            $sql = self::$conn->prepare('UPDATE cars SET model = ?, available = ?, image = ?, brands_id = ?, class_id = ?, car_body_id = ? WHERE id = ?');
            $sql->execute(array($data['model'], $data['available'], $data['image'], $data['brands_id'], $data['class_id'], $data['car_body_id'], $data['id']));
            $sql = self::$conn->prepare('DELETE FROM fuels_cars WHERE cars_id = ?');
            $sql->execute(array($data['id']));
            if(!empty($data['fuels'])){
                foreach ($data['fuels'] as $fuel){
                    $stmt = self::$conn->prepare("INSERT INTO fuels_cars (fuels_id, cars_id) VALUES (?, ?)");
                    $stmt->execute([$fuel, $data['id']]);
                }
            }
            self::$conn->commit();
        }catch (PDOException $exception){
            self::$conn->rollBack();
            echo $exception->getMessage();
        }
    }

    public static function All($limit = null, $orderBy = 'DESC')
    {;
        $carsCollection = array();
        $class = new self();
        if ($limit == null){
            $input = '';
        }else{
            $input = "LIMIT {$limit}";
        }
        $stmt = self::$conn->prepare("SELECT * FROM {$class->tableName} ORDER BY id {$orderBy} {$input}");
        $stmt->setFetchMode(PDO::FETCH_CLASS, "\App\Model\\".$class->class->getShortName());
        $stmt->execute();
        $getCars = $stmt->fetchAll();
        foreach ($getCars as $car){
            $car->price =  Price::find(['cars_id' => $car->id]);
            $car->brand = Brands::find(['id' => $car->brands_id]);
            $car->class = Classes::find(['id' => $car->class_id]);
            $car->carBody = CarBody::find(['id' => $car->car_body_id]);
            $stmt = self::$conn->prepare("SELECT fuels_id FROM fuels_cars WHERE cars_id = ?");
            $stmt->execute([$car->id]);
            $fuels_id = $stmt->fetchAll();
            foreach ($fuels_id as $fuel){
                $car->fuels[] = Fuels::find(['id' => $fuel['fuels_id']]);
            }
            $carsCollection[] = $car;
        }

        return $carsCollection;
    }

    public static function getAvailable() {
        $carsCollection = array();
        $class = new self();
        $stmt = self::$conn->prepare("SELECT * FROM cars WHERE available > 0  AND available != rent_reserved");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Cars::class);
        $stmt->execute();
        $getCars = $stmt->fetchAll();
        foreach ($getCars as $car){
            $car->price =  Price::find(['cars_id' => $car->id]);
            $car->brand = Brands::find(['id' => $car->brands_id]);
            $car->class = Classes::find(['id' => $car->class_id]);
            $car->carBody = CarBody::find(['id' => $car->car_body_id]);
            $stmt = self::$conn->prepare("SELECT fuels_id FROM fuels_cars WHERE cars_id = ?");
            $stmt->execute([$car->id]);
            $fuels_id = $stmt->fetchAll();
            foreach ($fuels_id as $fuel){
                $car->fuels[] = Fuels::find(['id' => $fuel['fuels_id']]);
            }
            $carsCollection[] = $car;
        }

        return $carsCollection;
    }


    public static function find($options = [], $limit = 0)
    {
        $carsCollection = array();
        $whereConditions = array();

        if(!empty($options)){
            foreach ($options as $key => $value){
                if ($value != 'null' and !is_int($value)){
                    $whereConditions[] = $key. " = '".$value."'";
                }else{
                    $whereConditions[] = $key. " = ".$value;
                }
            }

            $whereClause = "WHERE ".implode(' AND ', $whereConditions);

            $class = new self();
            $stmt = self::$conn->prepare("SELECT * FROM {$class->tableName} {$whereClause}");
            $stmt->setFetchMode(PDO::FETCH_CLASS, Cars::class);
            $stmt->execute();
            $cars = $stmt->fetchAll();
            if(count($cars)>1){
                foreach ($cars as $car){
                    $car->price =  Price::find(['cars_id' => $car->id]);
                    $car->brand = Brands::find(['id' => $car->brands_id]);
                    $car->class = Classes::find(['id' => $car->class_id]);
                    $car->carBody = CarBody::find(['id' => $car->car_body_id]);
                    $stmt = self::$conn->prepare("SELECT fuels_id FROM fuels_cars WHERE cars_id = ?");
                    $stmt->execute([$car->id]);
                    $fuels_id = $stmt->fetchAll();
                    foreach ($fuels_id as $fuel){
                        $car->fuels[] = Fuels::find(['id' => $fuel['fuels_id']]);
                    }
                    $carsCollection[] = $car;
                }
            }else{
                $car = $cars[0];
                $car->price =  Price::find(['cars_id' => $car->id]);
                $car->brand = Brands::find(['id' => $car->brands_id]);
                $car->class = Classes::find(['id' => $car->class_id]);
                $car->carBody = CarBody::find(['id' => $car->car_body_id]);
                $stmt = self::$conn->prepare("SELECT fuels_id FROM fuels_cars WHERE cars_id = ?");
                $stmt->execute([$car->id]);
                $fuels_id = $stmt->fetchAll();
                foreach ($fuels_id as $fuel){
                    $car->fuels[] = Fuels::find(['id' => $fuel['fuels_id']]);
                }
                $carsCollection = $car;
            }


        }
        return $carsCollection;
    }

    public function save()
    {
        $class = new ReflectionClass(Cars::class);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        unset($properties[0], $properties[11], $properties[12], $properties[13], $properties[14], $properties[15], $properties[9], $properties[10]);
        $toImplode = array();
        foreach ($properties as $property){
            if ($this->{$property->getName()} == 'null' or is_int($this->{$property->getName()})){
                $toImplode[] = $property->getName().' = '.$this->{$property->getName()};
            }else{
                $toImplode[] = $property->getName()." = '".$this->{$property->getName()}."'";
            }
        }

        $setClause = implode(', ', $toImplode);

        if (!empty($this->id)){
            $sql = "UPDATE {$this->tableName} SET {$setClause} WHERE id = $this->id";
        }else{
            $sql = "INSERT INTO {$this->tableName} SET {$setClause}";
        }

        try {
            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
        }catch (PDOException $exception){
            echo $exception->getMessage();
        }
    }

    public function hasFuels(array $fuels, int $car_id){
        try {
            $stmt = self::$conn->prepare('DELETE FROM fuels_cars WHERE cars_id = ?');
            $stmt->execute([$car_id]);
            foreach ($fuels as $fuel){
                $stmt = self::$conn->prepare('INSERT INTO fuels_cars (fuels_id, cars_id) VALUES (?,?)');
                $stmt->execute([$fuel, $car_id]);
            }
        }catch (PDOException $exception){
            throw new \Exception($exception->getMessage());
        }
    }


}