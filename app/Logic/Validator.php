<?php
declare(strict_types = 1);
namespace App\Logic;


class Validator
{
    /**
     * Validate not allowed characters in string
     * @param $str
     * @return bool
     */
    public static function validateString(string $str)
    {
        if(strpos($str, "=")!==false) {
            return false;
        }

        if(strpos($str, " ")!==false){
            return false;
        }

        if(strpos($str, "(")!==false){
            return false;
        }


        if(strpos($str, ")")!==false ){
            return false;
        }
        if(strpos($str, "'")!==false){
            return false;
        }


        if(strpos($str, "/")!==false){
            return false;
        }

        return true;
    }

    /**
     * Validate email function
     * @param $email
     * @return bool
     */
    public static function validateEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $exp1=explode('@', $email);
            if($exp1){
                $exp2=explode('.', $exp1[1]);
                if($exp2){
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else{
                return false;
            }
        } else {
            return false;
        }
    }
}