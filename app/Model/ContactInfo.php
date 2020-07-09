<?php
declare(strict_types = 1);
namespace App\Model;

use App\Logic\Model;

class ContactInfo extends Model
{
    protected $tableName = 'contact_info';

    public  $id;

    public  $name;

    public  $email;

    public  $mobile_number;

    public  $fix_number;

    public  $adress;

    public  $facebook_url;

    public  $instagram_url;

    public  $time_last_change;
}