<?php
namespace App\Controllers;

use App\Helpers;
use App\Model\Reviews;
use App\Model\ReviewsCars;
use Exception;

class ReviewsController
{
    public function reviews(){
        if (Helpers::CheckPermission()){
            $reviews = Reviews::All(150);
            echo json_encode($reviews);
        }
    }

    public function reviewsCars(){
        if (Helpers::CheckPermission()){
            $reviews = ReviewsCars::getReviews();
            echo json_encode($reviews);
        }
    }
}