<?php

namespace App\Model;

use App\Logic\Model;
use PDOException;
use PDO;
use ReflectionClass;
use ReflectionProperty;

class RentCars extends Model
{
    protected $tableName = 'rented_cars';

    public $id;

    public $name;

    public $lastname;

    public $email;

    public  $phone_number;

    public $JMBG;

    public $adress;

    public $allowed;

    public $rented_time;

    public $start_renting_date;

    public $end_renting_date;

    public $period_of_rent;

    public $price;

    public $time_added;

    public $cars_id;

    public $deleted;

    public $time_deleted;

    public $car;


    public static function create(array $data)
    {
        self::$conn->beginTransaction();
        try {
            $sql = self::$conn->prepare('INSERT INTO rented_cars (name, lastname, email, phone_number, JMBG, adress, period_of_rent , price, cars_id) VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)');
            $sql->execute(array($data['name'], $data['lastname'], $data['email'], $data['phone_number'], $data['JMBG'], $data['adress'], $data['period_of_rent'], $data['price'], $data['cars_id']));
            $sql = self::$conn->prepare('UPDATE cars SET rent_reserved = rent_reserved + 1, available = available - 1 WHERE  id = ?');
            $sql->execute(array($data['cars_id']));
            self::$conn->commit();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            self::$conn->rollBack();
        }
    }

    public static function find($options = [], $limit = 0)
    {
        $result = array();
        $whereConditions = array();

        if(!empty($options)){
            foreach ($options as $key => $value){
                $whereConditions[] = $key . ' = ' . $value;
            }

            $whereClause = "WHERE ".implode(' AND ', $whereConditions);

            $class = new self();

            $stmt = self::$conn->prepare("SELECT * FROM {$class->tableName} {$whereClause}");
            $stmt->setFetchMode(PDO::FETCH_CLASS, RentCars::class);
            $stmt->execute();
            $getData = $stmt->fetchAll();
            if(count($getData) > 1){
                foreach ($getData as $data){
                    $data->car = Cars::find(['id' => $data->cars_id]);
                    $date = date_create($data->start_renting_date);
                    $timestamp = date_timestamp_get($date);
                    $data->start_renting_date = date('D M d Y H:i:s O', $timestamp);
                    $result[] = $data;
                }
            }elseif(count($getData) == 1){
                $getData[0]->car = Cars::find(['id' => $getData[0]->cars_id]);
                $date = date_create($getData[0]->start_renting_date);
                $timestamp = date_timestamp_get($date);
                $getData[0]->start_renting_date = date('D M d Y H:i:s O', $timestamp);
                $result = $getData[0];
            }
        }

        return $result;
    }

    public static function delete(int $id)
    {
        $rentedcar = self::find(['id' => $id]);
        $car = Cars::find(['id' => $rentedcar->cars_id]);
        $car->available = $car->available + 1;
        $car->rent_reserved = $car->rent_reserved -1;
        $car->save();
        $time_deleted = date('m/d/Y H:i:s', time());
        $rentedcar->deleted = 1;
        $rentedcar->time_deleted = $time_deleted;
        $rentedcar->save();
    }

    public function save()
    {
        $properties = $this->class->getProperties(ReflectionProperty::IS_PUBLIC);
        unset($properties[0], $properties[13], $properties[16], $properties[17]);
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
            throw new \Exception($exception->getMessage());
        }
    }


    public function addUserRenter(array $data)
    {
        $stmt = self::$conn->prepare('INSERT INTO rented_cars_has_users (users_id, rented_cars_id) VALUES (?, ?)');
        $stmt->execute([$data['user_id'], $data['rent_car_id']]);
    }

    public static function getRentedCarsIdUser(int $id) : array
    {
        $stmt = self::$conn->prepare('SELECT * FROM rented_cars_has_users WHERE users_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
}