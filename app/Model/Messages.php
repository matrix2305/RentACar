<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;
class Messages extends Model
{
    public $id;

    public $name;

    public $lastname;

    public $email;

    public $message;

    public $deleted;
}