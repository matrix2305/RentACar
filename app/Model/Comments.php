<?php
namespace App\Model;


use App\Logic\Model;

class Comments extends Model
{
    public $id;

    public $name;

    public $email;

    public $comment;

    public $allowed;

    public $deleted;

    public $cars_id;

    public $created_at;
}