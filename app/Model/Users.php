<?php
namespace App\Model;

use App\Logic\Model;
use PDO;
use ReflectionProperty;
use PDOException;

class Users extends Model
{

    public $id;

    public $username;

    public $email;

    public $password;

    public $name;

    public $lastname;

    public $adress;

    public $phone_number;

    public $avatar_name;

    public $roles_id;

    public $role;

    public static function All($limit = null, $orderBy = 'DESC')
    {
        $usersCollection = array();
        if ($limit == null){
            $input = '';
        }else{
            $input = "LIMIT {$limit}";
        }
        $class = new self();
        $stmt = self::$conn->prepare("SELECT * FROM {$class->tableName} ORDER BY id {$orderBy} {$input}");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Users::class);
        $stmt->execute();
        $users = $stmt->fetchAll();
        if(count($users) > 1){
            foreach ($users as $user){
                $user->role = Roles::find(['id' => $user->roles_id]);
                $usersCollection[] = $user;
            }
        }else {
            $users[0]->role = Roles::find(['id' => $users[0]->roles_id]);
            $usersCollection = $users[0];
        }

        return $usersCollection;
    }

    public static function find($options = [], $limit = 0)
    {
        $result = array();
        $whereConditions = array();

        if(!empty($options)){
            foreach ($options as $key => $value){
                $whereConditions[] = $key . " = '" . $value.  "'";
            }

            $whereClause = "WHERE ".implode(' AND ', $whereConditions);
            $class = new self();
            $stmt = self::$conn->prepare("SELECT * FROM {$class->tableName} {$whereClause}");
            $stmt->setFetchMode(PDO::FETCH_CLASS, Users::class);
            $stmt->execute();
            $getData = $stmt->fetchAll();
            $total = $stmt->rowCount();
            if($total > 1){
                foreach ($getData as $data){
                    $data->role = Roles::find(['id' => $data->roles_id]);
                    $result[] = $data;
                }
            }elseif(count($getData) == 1){
                $getData[0]->role = Roles::find(['id' => $getData[0]->roles_id]);
                $result = $getData[0];
            }
        }

        return $result;
    }

    public static function update(array $data, $where = null)
    {
        $class = new self();
        if (!empty($where)){
            $key = array_keys($where);
            $values = array_values($where);
            $whereCondition = "WHERE {$key[0]} = $values[0]";
        }else{
            $whereCondition = 'WHERE id = '. $data['id'];
            unset($data['id']);
        }

        $password = $data['password'];
        unset($data['password']);

        $values = array();

        foreach ($data as $key=>$value){
            if ($value != 'null' and !is_int($value)){
                $values[] = $key. " = '".$value."'";
            }else{
                $values[] = $key. " = ".$value;
            }
        }

        $values = implode(', ', $values);

        try {
            $stmt = self::$conn->prepare("UPDATE {$class->tableName} SET {$values} {$whereCondition}");
            $stmt->execute();

            if (!empty($password)){
                $stmt = self::$conn->prepare("UPDATE {$class->tableName} SET password = ? {$whereCondition}");
                $stmt->execute([$password]);
            }
        }catch (PDOException $exception){
            echo $exception->getMessage();
        }
    }

    public function save()
    {
        $properties = $this->class->getProperties(ReflectionProperty::IS_PUBLIC);
        unset($properties[0], $properties[10]);
        if ($this->password == 'undefined'){
            unset($properties[3]);
        }
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

}