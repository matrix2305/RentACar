<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;

class Ratings extends Model
{
    public $id;

    public $name;

    public $rating;

    public $comment;

    public $allowed;

    public $deleted;

}