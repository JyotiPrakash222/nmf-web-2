<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Model\BaseModelController;
use App\Http\Controllers\Api\BaseApiController;
/**
 * Description of YesAwayApiController
 *
 * @author apple
 */
class NissaApiController extends BaseApiController {

    private static $baseUrl = "http://nissaxml.turevrac.com";
    private static $keyHack = "DVd5.GtfE4yW";
    private static $requestTimeout = 60;

    private static $apiUsername = "NissaCar4HireS";
    private static $apiPassword = "CaR4HireS*Nissa1907";
    private static $apiDefaultCurrency = "USD";
    
    public static function getAllLocationsDownload()
    {
        self::getLocations(1);
    }
    public static function getAllLocations()
    {
        self::getLocations(0,1);
    }
    
    /*
     * like
     * http://nissaxml.turevrac.com/XML_Locations.Asp?Key_Hack=DVd5.GtfE4yW
     */
    public static function getLocations($is_download = 0, $is_json_string = 0)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          //CURLOPT_URL => "http://nissaxml.turevrac.com/XML_Locations.Asp?Key_Hack=DVd5.GtfE4yW",
          CURLOPT_URL => self::$baseUrl."/XML_Locations.Asp?Key_Hack=".self::$keyHack,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
            if($is_download == 0) {
                if($is_json_string == 1) {
                    echo json_encode(self::convertXMLResponseToArray($response), JSON_PRETTY_PRINT);
                } else {
                    return self::convertXMLResponseToArray($response);
                }
            }
            else {
                self::convertXMLResponseToExcel($response);
            }
        } else {
          echo "Unknown Error";  
        }
    }
    

    /**
     * like
     * http://nissaxml.turevrac.com/xml_Rez.asp?Key_Hack=DVd5.GtfE4yW&Drop_Off_ID=33&Pickup_ID=33&Pickup_Day=01&Pickup_Month=04&Pickup_Year=2023&Drop_Off_Day=07&Drop_Off_Month=04&Drop_Off_Year=2023&Pickup_Hour=23&Pickup_Min=00&Drop_Off_Hour=23&Drop_Off_Min=00&User_Name=NissaCar4HireS&User_Pass=CaR4HireS*Nissa1907&Currency=TL
     */
    public static function searchVehiclesForLocation($input)
    {
        //$input = $request->input();
        $dropOffLocationId = isset($input['dropoff_location_id']) ? $input['dropoff_location_id'] : '';
        $pickUpLocationId = isset($input['pickup_location_id']) ? $input['pickup_location_id'] : '';
        $pickUpDay = isset($input['pickup_day']) ? $input['pickup_day'] : '';
        $pickUpMonth = isset($input['pickup_month']) ? $input['pickup_month'] : '';
        $pickUpYear = isset($input['pickup_year']) ? $input['pickup_year'] : '';
        $dropOffDay = isset($input['dropoff_day']) ? $input['dropoff_day'] : '';
        $dropOffMonth = isset($input['dropoff_month']) ? $input['dropoff_month'] : '';
        $dropOffYear = isset($input['dropoff_year']) ? $input['dropoff_year'] : '';
        $pickUpHour = isset($input['pickup_hour']) ? $input['pickup_hour'] : '';
        $pickUpMin = isset($input['pickup_min']) ? $input['pickup_min'] : '';
        $dropOffHour = isset($input['dropoff_hour']) ? $input['dropoff_hour'] : '';
        $dropOffMin = isset($input['dropoff_min']) ? $input['dropoff_min'] : '';
        $currency = isset($input['currency']) ? $input['currency'] : self::$apiDefaultCurrency;

        $curl = curl_init();
        curl_setopt_array($curl, array(
         //CURLOPT_URL => "http://nissaxml.turevrac.com/xml_Rez.asp?Key_Hack=DVd5.GtfE4yW&Drop_Off_ID=33&Pickup_ID=33&Pickup_Day=01&Pickup_Month=04&Pickup_Year=2023&Drop_Off_Day=07&Drop_Off_Month=04&Drop_Off_Year=2023&Pickup_Hour=23&Pickup_Min=00&Drop_Off_Hour=23&Drop_Off_Min=00&User_Name=NissaCar4HireS&User_Pass=CaR4HireS*Nissa1907&Currency=USD",
          CURLOPT_URL => self::$baseUrl."/xml_Rez.asp?Key_Hack=".self::$keyHack."&Drop_Off_ID=".$dropOffLocationId."&Pickup_ID=".$pickUpLocationId."&Pickup_Day=".$pickUpDay."&Pickup_Month=".$pickUpMonth."&Pickup_Year=".$pickUpYear."&Drop_Off_Day=".$dropOffDay."&Drop_Off_Month=".$dropOffMonth."&Drop_Off_Year=".$dropOffYear."&Pickup_Hour=".$pickUpHour."&Pickup_Min=".$pickUpMin."&Drop_Off_Hour=".$dropOffHour."&Drop_Off_Min=".$dropOffMin."&User_Name=".self::$apiUsername."&User_Pass=".self::$apiPassword."&Currency=".$currency,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
            $data = self::convertXMLResponseToArray($response);
            return self::formatData($data,$input);
        } else {
            return response()->json(['message' => "Unknown Error", 'status' => 0], 200);
        }
    }
    
    public static function searchVehiclesForLocationTest(Request $request)
    {
        $input = $request->input();
        $dropOffLocationId = isset($input['dropoff_location_id']) ? $input['dropoff_location_id'] : '';
        $pickUpLocationId = isset($input['pickup_location_id']) ? $input['pickup_location_id'] : '';
        $pickUpDay = isset($input['pickup_day']) ? $input['pickup_day'] : '';
        $pickUpMonth = isset($input['pickup_month']) ? $input['pickup_month'] : '';
        $pickUpYear = isset($input['pickup_year']) ? $input['pickup_year'] : '';
        $dropOffDay = isset($input['dropoff_day']) ? $input['dropoff_day'] : '';
        $dropOffMonth = isset($input['dropoff_month']) ? $input['dropoff_month'] : '';
        $dropOffYear = isset($input['dropoff_year']) ? $input['dropoff_year'] : '';
        $pickUpHour = isset($input['pickup_hour']) ? $input['pickup_hour'] : '';
        $pickUpMin = isset($input['pickup_min']) ? $input['pickup_min'] : '';
        $dropOffHour = isset($input['dropoff_hour']) ? $input['dropoff_hour'] : '';
        $dropOffMin = isset($input['dropoff_min']) ? $input['dropoff_min'] : '';
        $currency = isset($input['currency']) ? $input['currency'] : self::$apiDefaultCurrency;

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://nissaxml.turevrac.com/xml_Rez.asp?Key_Hack=DVd5.GtfE4yW&Drop_Off_ID=33&Pickup_ID=33&Pickup_Day=01&Pickup_Month=09&Pickup_Year=2024&Drop_Off_Day=07&Drop_Off_Month=09&Drop_Off_Year=2024&Pickup_Hour=23&Pickup_Min=00&Drop_Off_Hour=23&Drop_Off_Min=00&User_Name=NissaCar4HireS&User_Pass=CaR4HireS*Nissa1907&Currency=USD",
//          CURLOPT_URL => self::$baseUrl."/xml_Rez.asp?Key_Hack=".self::$keyHack."&Drop_Off_ID=".$dropOffLocationId."&Pickup_ID=".$pickUpLocationId."&Pickup_Day=".$pickUpDay."&Pickup_Month=".$pickUpMonth."&Pickup_Year=".$pickUpYear."&Drop_Off_Day=".$dropOffDay."&Drop_Off_Month=".$dropOffMonth."&Drop_Off_Year=".$dropOffYear."&Pickup_Hour=".$pickUpHour."&Pickup_Min=".$pickUpMin."&Drop_Off_Hour=".$dropOffHour."&Drop_Off_Min=".$dropOffMin."&User_Name=".self::$apiUsername."&User_Pass=".self::$apiPassword."&Currency=".$currency,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
            $data = self::convertXMLResponseToArray($response);
            return self::formatData($data,$input);
        } else {
            return response()->json(['message' => "Unknown Error", 'status' => 0], 200);
        }
    }
    
    private static function formatData($data,$input) {
        if(isset($data['Car']) && is_array($data['Car']) && !empty($data['Car'])) {            
            $currencyModel = self::getCurrency($input);
            $responseData['vehicles'] = [];
            $conversionAmount = NULL;
            $commission = BaseApiController::getCommission($input['vendor_id']);
            foreach($data['Car'] as $object) {
                $object['vehicle_name'] = (isset($object['Car_Name']) && !empty($object['Car_Name'])) ? $object['Car_Name'] : '';
                $object['vehicle_image'] = (isset($object['Image_Path']) && !empty($object['Image_Path'])) ? $object['Image_Path'] : '';
                
                $object['others'] = [];
                $transmissionType = (isset($object['Transmission']) && !empty($object['Transmission'])) ? $object['Transmission'] : '';;
                if($transmissionType == 'Automatic') {
                    $object['others'][] = 'Automatic Transmission';
                }
                $object['passenger'] = isset($object['Chairs']) ? $object['Chairs'] : "";
                $object['bag'] = isset($object['Small_Bags']) ? $object['Small_Bags'] : "";
                
                
                $object['vehicle_actual_price'] = (isset($object['Daily_Rental']) && !empty($object['Daily_Rental'])) ? $object['Daily_Rental'] : '';
                $object['vehicle_price'] = BaseApiController::getVehiclePriceWithCommission($commission, $object['vehicle_actual_price']);
                $object['vehicle_price_currency'] = (isset($input['currency']) && !empty($input['currency'])) ? $input['currency'] : self::$apiDefaultCurrency;
                if(!isset($conversionAmount)) {
                    $conversionAmount = BaseModelController::getCurrencyValue($object['vehicle_price_currency'],session()->get('currency_name'));
                }
                $object['vehicle_price_local'] = $conversionAmount*$object['vehicle_price'];
                $object['vehicle_price_currency_local'] = session()->get('currency_name');
                $object['vendor_id'] = $input['vendor_id'];
                if(isset($currencyModel) && !empty($currencyModel)) {
                    $object['vehicle_price_symbol'] = isset($currencyModel->symbol) ? $currencyModel->symbol : NULL;
                    $object['vehicle_price_currency_name'] = isset($currencyModel->currency_name) ? $currencyModel->currency_name : NULL;
                }
                $responseData['vehicles'][] = $object;
            }
            return $responseData;
        }
    }
    
    private static function getCurrency($input) {
        $vehicle_price_currency = isset($input['currency']) ? $input['currency'] : self::$apiDefaultCurrency;
        $countryModel = \App\Models\CountryModel::select('symbol','currency_name')->where('currency',$vehicle_price_currency)->first();
        if(isset($countryModel) && !empty($countryModel)) {
            return $countryModel;
        }
        return [];
    }
}
