<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;

class Classes extends Model
{
    protected $tableName = 'class';

    public $id;

    public $class_name;

    public $class_color;

}