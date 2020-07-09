<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;
class Reviews extends Model
{
    public $id;

    public $path;

    public $reviews;

    public static function UpdateReview($urlpath) {
        $class = new self();
        $stmt = $class->conn->prepare('UPDATE reviews SET reviews = reviews + 1  WHERE path = ?');
        $stmt->execute([$urlpath]);
    }
}