<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;

class CarBody extends Model
{
    protected $tableName = 'car_body';

    public $id;

    public $car_body_name;
}