<?php
namespace App\Utility;

class TripTimeValues{
    public static $time = [
        ['text'=> "06:00 AM", 'value' => "06:00"],
        ['text'=> "06:30 AM", 'value' => "06:30"],
        ['text'=> "07:00 AM", 'value' => "07:00"],
        ['text'=> "07:30 AM", 'value' => "07:30"],
        ['text'=> "08:00 AM", 'value' => "08:00"],
        ['text'=> "08:30 AM", 'value' => "08:30"],
        ['text'=> "09:00 AM", 'value' => "09:00"],
        ['text'=> "09:30 AM", 'value' => "09:30"],
        ['text'=> "10:00 AM", 'value' => "10:00"],
        ['text'=> "10:30 AM", 'value' => "10:30"],
        ['text'=> "11:00 AM", 'value' => "11:00"],
        ['text'=> "11:30 AM", 'value' => "11:30"],
        ['text'=> "12:00 PM", 'value' => "12:00"], 
        ['text'=> "12:30 PM", 'value' => "12:30"],
        ['text'=> "01:00 PM", 'value' => "13:00"],
        ['text'=> "01:30 PM", 'value' => "13:30"],
        ['text'=> "02:00 PM", 'value' => "14:00"],
        ['text'=> "02:30 PM", 'value' => "14:30"],
        ['text'=> "03:00 PM", 'value' => "15:00"],
        ['text'=> "03:30 PM", 'value' => "15:30"],
        ['text'=> "04:00 PM", 'value' => "16:00"],
        ['text'=> "04:30 PM", 'value' => "16:30"],
        ['text'=> "05:00 PM", 'value' => "17:00"],
        ['text'=> "05:30 PM", 'value' => "17:30"],
        ['text'=> "06:00 PM", 'value' => "18:00"],
        ['text'=> "06:30 PM", 'value' => "18:30"],
        ['text'=> "07:00 PM", 'value' => "19:00"],
        ['text'=> "07:30 PM", 'value' => "19:30"],
        ['text'=> "08:00 PM", 'value' => "20:00"],
        ['text'=> "08:30 PM", 'value' => "20:30"],
        ['text'=> "09:00 PM", 'value' => "21:00"],
        ['text'=> "09:30 PM", 'value' => "21:30"],
        ['text'=> "10:00 PM", 'value' => "22:00"],
        ['text'=> "10:30 PM", 'value' => "22:30"],
        ['text'=> "11:00 PM", 'value' => "23:00"],
        ['text'=> "11:30 PM", 'value' => "23:30"]
    ];
    public static function getTripTimeValues(){
        return self::$time;
    }
}
 
?>