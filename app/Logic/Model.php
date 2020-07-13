<?php
namespace App\Logic;

use ReflectionClass;
use ReflectionProperty;
use PDO;
use PDOException;
use App\Logic\Connection;

class Model extends Connection
{
    protected $tableName;

    protected $class;

    protected $className;

    protected $properties;

    protected static $conn;

    public function __construct()
    {

        self::$conn = $this->getInstance();

        $this->class = new ReflectionClass(get_class($this));
        if(empty($this->tableName)){
            $this->tableName = strtolower($this->class->getShortName());
        }

        $this->className = $this->class->getShortName();

        $propsToImplode = array();

        foreach ($this->class->getProperties(ReflectionProperty::IS_PUBLIC) as $property){
            $propertyName = $property->getName();
            $propsToImplode[] = $propertyName;
        }

        $this->properties = implode(', ', $propsToImplode);
    }

    public static function All($limit = null, $orderBy = 'DESC')
    {
        $name = '\\'.get_called_class();
        $class = new $name;
        if ($limit == null){
            $input = '';
        }else{
            $input = "LIMIT {$limit}";
        }
        $stmt = self::$conn->prepare("SELECT {$class->properties} FROM {$class->tableName} ORDER BY id {$orderBy} {$input}");
        $stmt->setFetchMode(PDO::FETCH_CLASS, "\App\Model\\".$class->class->getShortName());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find($options = [], $limit = 0)
    {
        $result = array();
        $whereConditions = array();

        if(!empty($options)){
            foreach ($options as $key => $value){
                if ($value != 'null' and !is_int($value)){
                    $whereConditions[] = $key. " = '".$value."'";
                }else{
                    $whereConditions[] = $key. " = ".$value;
                }
            }

            if (!empty($limit)){
                $limit = "LIMIT $limit";
            }else{
                $limit = '';
            }

            $whereClause = "WHERE ".implode(' AND ', $whereConditions);
            $name = '\\'.get_called_class();
            $class = new $name;
            $stmt = self::$conn->prepare("SELECT {$class->properties} FROM {$class->tableName} {$whereClause} $limit");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "\App\Model\\".$class->class->getShortName());
            $stmt->execute();
            $getData = $stmt->fetchAll();
            if(count($getData)>1){
                foreach ($getData as $data){
                    $result[] = $data;
                }
            }elseif(count($getData) == 1){
                $result = $getData[0];
            }
        }

        return $result;
    }

    public static function create(array $data)
    {
        $name = '\\'.get_called_class();
        $class = new $name;
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));
        $stmt = self::$conn->prepare("INSERT INTO {$class->tableName} ({$keys}) VALUES ({$values})" );
        $stmt->execute();
    }

    public static function update(array $data, $where = null)
    {
        $name = '\\'.get_called_class();
        $class = new $name;
        if (!empty($where)){
            $key = array_keys($where);
            $values = array_values($where);
            $whereCondition = "WHERE {$key[0]} = $values[0]";
        }else{
            $whereCondition = 'WHERE id = '. $data['id'];
            unset($data['id']);
        }

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
        }catch (PDOException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    public static function delete(int $id)
    {
        $name = '\\'.get_called_class();
        $class = new $name;
        $stmt = self::$conn->prepare("DELETE FROM {$class->tableName} WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function save(){
        $properties = $this->class->getProperties(ReflectionProperty::IS_PUBLIC);
        unset($properties[0]);
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


    public function lastInsertedId(){
        return self::$conn->lastInsertId();
    }
}