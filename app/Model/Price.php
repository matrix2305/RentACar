<?php
declare(strict_types = 1);
namespace App\Model;


use App\Logic\Model;

class Price extends Model
{
    public $id;

    public $one_day;

    public $two_days;

    public $three_days;

    public $seven_days;

    public $fourteen_days;

    public $cars_id;
}