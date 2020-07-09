<?php
namespace App\Model;

use App\Logic\Model;
class MapCars extends Model
{
    protected $tableName = 'monitoring_cars';

    public $id;

    public $x_position;

    public $y_position;

    public $time;

    public $rented_cars_id;
}