<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Utility\Utility;
use App\Http\Controllers\Model\BaseModelController;
use App\Http\Controllers\Api\BaseApiController;

/**
 * Description of YesAwayApiController
 *
 * @author apple
 */
class EasirentApiController extends BaseApiController {

    private static $baseUrl = "https://easirent.com/broker/car4hires/livefeed.asp";
    private static $requestTimeout = 60;
    private static $apiCode = '$BRO345'; // Which we need to capture from DB
    private static $vehicleType = 1; // 1 for cars
    private static $estimateMiles = 100; // 100 static given in api
    private static $apiCurrency;
    private static $bookingPrefix = "car4hires";
    private static $apiCurrencyModel;
        
    public static function createBookingArray($data){
      /*$data['start_date'] = $data['start_date']; //2023-04-28
      $data['end_date'] = $data['end_date']; // 2023-04-28
      $data['start_time'] = $data['start_time']; // 10:00
      $data['end_time'] =  $data['end_time']; // 10:00
      $data['pickup']= $data['pickup'];
      $data['dropoff']= $data['dropoff'];*/
      $cars_data = json_decode($data['cars'], true);
      $data['hiredays'] = $cars_data['hiredays'];
      $data['vehicle_id'] = isset($cars_data['id']) ? $cars_data['id'] : '';
      $data['group']= isset($cars_data['group']) ? $cars_data['group'] : '';
      $data['currency'] = session()->get('currency_name');
      $data['vehicle_actual_currency'] = $cars_data['vehicle_price_currency'];
      $data['company_name']= $cars_data['company_name'];
      $data['api_url_code']= $cars_data['api_url_code'];
      $data['vehicle_price']=  $cars_data['vehicle_price'];
      $data['vehicle_actual_price']=  $cars_data['vehicle_actual_price'];
      $data['vehicle_name'] =  $cars_data['vehicle_name'];
      $data['vendor_id'] =  $cars_data['vendor_id'];
      return $data;
    }
    public static function bookingStore(){
      $personal_detail = session()->get('personal_detail');
      $booking = session()->get('booking');
      $pickup = \App\Http\Controllers\Model\BaseModelController::getLocationById($booking['pickup']);
      $dropoff = \App\Http\Controllers\Model\BaseModelController::getLocationById($booking['dropoff']);
      $data['pickup_date'] = date('Y-m-d', strtotime($booking['start_date'])); //2023-04-28
      $data['dropoff_date'] = date('Y-m-d', strtotime($booking['end_date'])); // 2023-04-28
      $data['pickup_time'] = $booking['start_time']; // 10:00
      $data['dropoff_time'] =  $booking['end_time']; // 10:00
      $data['user']['first_name'] = $personal_detail['firstname'];
      $data['user']['last_name'] = $personal_detail['lasttname'];
      $data['user']['phone_code'] = $personal_detail['mobile_code'];
      $data['user']['phone'] = $personal_detail['mobile_no'];
      $data['vehicle']['id'] = $booking['vehicle_id'];
      $data['vehicle']['group'] = $booking['group'];
      $data['vehicle']['hiredays'] = $booking['hiredays'];
      $data['vehicle']['price'] = $booking['vehicle_actual_price'];
      $data['vehicle']['currency'] = $booking['vehicle_actual_currency'];
      $data['pickup_location_code'] = isset($pickup['code']) ? $pickup['code'] : '';
      $data['dropoff_location_code'] = isset($dropoff['code']) ? $dropoff['code'] : '';
      $data['booking_id'] = isset($personal_detail['id']) ? $personal_detail['id'] : '';
      $data['country_code'] = $pickup['country_code'];
      return $data;
  } 
  
    public static function getAllLocationsDownload()
    {
        self::getLocations(1);
    }
    public static function getAllLocations()
    {
        self::getLocations(0,1);
    }

    public static function getLocations($is_download = 0, $is_json_string = 0)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$baseUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => self::$requestTimeout,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                                    <GetLocations>
                                        <bcode>".self::$apiCode."</bcode>
                                   </GetLocations>",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
//            echo "<pre>", print_r($response);die;
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
    

   
    public static function searchVehiclesForLocationTest(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::$baseUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                                    <GetVehicles>
                                       <bcode>".self::$apiCode."</bcode>
                                       <vtype>".self::$vehicleType."</vtype>
                                       <estmiles>".self::$estimateMiles."</estmiles>
                                       <pickup>
                                           <location>BIR</location>
                                           <date>2024-10-22</date>
                                           <time>10:00</time>
                                       </pickup>
                                       <dropoff>
                                           <location>BIR</location>
                                           <date>2024-10-28</date>
                                           <time>22:00</time>
                                       </dropoff>
                                   </GetVehicles>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
            $data = self::convertXMLResponseToArray($response);
            return response()->json(self::formatVehiclesForLocationData($data,4));
        } else {
          echo "Unknown Error";  
        }
    }
    
    /*
     * $pickUpDateTime - 2023-09-22T16:00:00
     * $dropOffDateTime - 2023-09-29T10:00:00
     * $pickUpLocationCode - HKT01
     * $dropOffLocationCode - HKT01
     */
    public static function searchVehiclesForLocation($input)
    {
        $pickUpDate = isset($input['pickup_date']) ? $input['pickup_date'] : ''; //2023-04-28
        $dropOffDate = isset($input['dropoff_date']) ? $input['dropoff_date'] : ''; // 2023-04-28
        $pickUpTime = isset($input['pickup_time']) ? $input['pickup_time'] : ''; // 10:00
        $dropOffTime = isset($input['dropoff_time']) ? $input['dropoff_time'] : ''; // 10:00
        $pickUpLocationCode = isset($input['pickup_location_code']) ? $input['pickup_location_code'] : '';
        $dropOffLocationCode = isset($input['dropoff_location_code']) ? $input['dropoff_location_code'] : '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::$baseUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                                    <GetVehicles>
                                        <bcode>".self::$apiCode."</bcode>
                                        <vtype>".self::$vehicleType."</vtype>
                                        <estmiles>".self::$estimateMiles."</estmiles>
                                        <pickup>
                                            <location>".$pickUpLocationCode."</location>
                                            <date>".$pickUpDate."</date>
                                            <time>".$pickUpTime."</time>
                                        </pickup>
                                        <dropoff>
                                            <location>".$dropOffLocationCode."</location>
                                            <date>".$dropOffDate."</date>
                                            <time>".$dropOffTime."</time>
                                        </dropoff>
                                   </GetVehicles>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
//                self::convertXMLResponseToFile($response);
            $data = self::convertXMLResponseToArray($response);
//                Utility::sendTestEmail($data);
            return self::formatVehiclesForLocationData($data, $input['vendor_id']);
            //return response()->json(self::formatVehiclesForLocationData($data));
        } else {
          echo "Unknown Error";  
        }
    }
    
    private static function formatVehiclesForLocationData($data, $vendor_id) {
        if(isset($data['vehicle']) && is_array($data['vehicle']) && !empty($data['vehicle'])) {            
            $vehicles_data = $data['vehicle'];
            $responseData['vehicles'] = [];
            $conversionAmount = NULL;
            $commission = BaseApiController::getCommission($vendor_id);
            foreach($vehicles_data as $object) {
                $object['vehicle_name'] = (isset($object['model']) && !empty($object['model'])) ? $object['model'] : '';
                $object['vehicle_image'] = (isset($object['image']) && !empty($object['image'])) ? $object['image']  : '';
                
                $object['others'] = [];
                $airConditionInd = isset($object['aircon']) ? $object['aircon'] : '';
                if($airConditionInd == 'yes') {
                    $object['others'][] = 'Air Conditioning';
                }
                $transmissionType = (isset($object['transmition']) && !empty($object['transmition'])) ? $object['transmition'] : '';;
                if($transmissionType == 'Automatic') {
                    $object['others'][] = 'Automatic Transmission';
                }
                $object['passenger'] = isset($object['people']) ? $object['people'] : "";
                $object['bag'] = isset($object['luggage']) ? $object['luggage'] : "";
                $object['door'] = isset($object['doors']) ? $object['doors'] : "";

                $object['vehicle_actual_price'] = (isset($object['price']) && !empty($object['price'])) ? $object['price'] : '';
                $object['vehicle_price'] = BaseApiController::getVehiclePriceWithCommission($commission, $object['vehicle_actual_price']);
                $object['vehicle_price_currency'] = (isset($object['currency']) && !empty($object['currency'])) ? $object['currency'] : self::$apiCurrency;
                if(!isset($conversionAmount)) {
                    $conversionAmount = BaseModelController::getCurrencyValue($object['vehicle_price_currency'],session()->get('currency_name'));
                }
                $object['vehicle_price_local'] = $conversionAmount*$object['vehicle_price'];
                $object['vehicle_price_currency_local'] = session()->get('currency_name');
                $object['vendor_id'] = $vendor_id;
                $currencyModel = self::getCurrency($object);
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
        $vehicle_price_currency = (isset($input['currency']) && !empty($input['currency'])) ? $input['currency'] : '';
        if(isset($vehicle_price_currency) && !empty($vehicle_price_currency)) {
            if(isset(self::$apiCurrencyModel) && !empty(self::$apiCurrencyModel)) {
                $currency = isset(self::$apiCurrencyModel->currency) ? self::$apiCurrencyModel->currency : '';
                if($currency == $vehicle_price_currency) {
                    return self::$apiCurrencyModel;
                }
            }
            $currencyModel = \App\Models\CountryModel::select('symbol','currency_name','currency')->where('currency',$vehicle_price_currency)->first();
            if(isset($currencyModel) && !empty($currencyModel)) {
                self::$apiCurrencyModel = $currencyModel;
                return self::$apiCurrencyModel;
            }
        }
    }
    
    public static function bookVehicleForLocationTest()
    {        
        $pickUpDate = '2023-06-24'; //2023-04-28
        $dropOffDate = '2023-06-25'; // 2023-04-28
        $pickUpTime = '10:00'; // 10:00
        $dropOffTime = '11:00'; // 10:00
        $pickUpLocationCode = 'LPL';
        $dropOffLocationCode = 'LPL';
        $bookingId = '59';
//        $bookingTime = strtotime(date("d-m-Y H:i:s"));
        $user = [
            'first_name' => 'test',
            'last_name' => 'test',
            'phone_code' => 'test',
            'phone' => '7007692445',
        ];
        $vehicle = [
            'id' => '297',
            'group' => 'EB',
            'price' => '194.04',
            'currency' => 'GBP',
            'hiredays' => '3',
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::$baseUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                                    <MakeReservation>
                                        <bcode>".self::$apiCode."</bcode>
                                        <reservation>
                                           <agencyreference>".self::$bookingPrefix.'-'.$bookingId."</agencyreference>
                                            <customer>
                                                <name>".(isset($user['first_name']) ? $user['first_name'] : '')."</name>
                                                <surname>".(isset($user['last_name']) ? $user['last_name'] : '')."</surname>
                                                <phone>".(isset($user['phone_code']) ? $user['phone_code'] : '').(isset($user['phone']) ? $user['phone'] : '')."</phone>
                                                <notes></notes>
                                            </customer>
                                            <vehicle>
                                                <id>".(isset($vehicle['id']) ? $vehicle['id'] : '')."</id>
                                                <group>".(isset($vehicle['group']) ? $vehicle['group'] : '')."</group>
                                            </vehicle>
                                            <pickup>
                                                <location>".$pickUpLocationCode."</location>
                                                <date>".$pickUpDate."</date>
                                                <time>".$pickUpTime."</time>
                                            </pickup>
                                            <dropoff>
                                                <location>".$dropOffLocationCode."</location>
                                                <date>".$dropOffDate."</date>
                                                <time>".$dropOffTime."</time>
                                            </dropoff>
                                            <days>".(isset($vehicle['hiredays']) ? $vehicle['hiredays'] : '')."</days>
                                            <price>".(isset($vehicle['price']) ? $vehicle['price'] : '')."</price>
                                            <currency>".(isset($vehicle['currency']) ? $vehicle['currency'] : '')."</currency>
                                        </reservation>
                                    </MakeReservation>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
//                self::convertXMLResponseToFile($response);
            return self::convertXMLResponseToArray($response);
//                Utility::sendTestEmail($data);
        } else {
          echo "Unknown Error";  
        }
    }
    
    public static function bookVehicleForLocation($input)
    {        
        $pickUpDate = isset($input['pickup_date']) ? $input['pickup_date'] : ''; //2023-04-28
        $dropOffDate = isset($input['dropoff_date']) ? $input['dropoff_date'] : ''; // 2023-04-28
        $pickUpTime = isset($input['pickup_time']) ? $input['pickup_time'] : ''; // 10:00
        $dropOffTime = isset($input['dropoff_time']) ? $input['dropoff_time'] : ''; // 10:00
        $pickUpLocationCode = isset($input['pickup_location_code']) ? $input['pickup_location_code'] : '';
        $dropOffLocationCode = isset($input['dropoff_location_code']) ? $input['dropoff_location_code'] : '';
        $bookingId = isset($input['booking_id']) ? $input['booking_id'] : '';
        $bookingTime = time();//strtotime(date("d-m-Y H:i:s"));
        $user = isset($input['user']) ? $input['user'] : [];
        $vehicle = isset($input['vehicle']) ? $input['vehicle'] : [];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::$baseUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::$requestTimeout,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                                    <MakeReservation>
                                        <bcode>".self::$apiCode."</bcode>
                                        <reservation>
                                           <agencyreference>".self::$bookingPrefix.'-'.$bookingId.'-'.$bookingTime."</agencyreference>
                                            <customer>
                                                <name>".(isset($user['first_name']) ? $user['first_name'] : '')."</name>
                                                <surname>".(isset($user['last_name']) ? $user['last_name'] : '')."</surname>
                                                <phone>".(isset($user['phone_code']) ? $user['phone_code'] : '').(isset($user['phone']) ? $user['phone'] : '')."</phone>
                                                <notes></notes>
                                            </customer>
                                            <vehicle>
                                                <id>".(isset($vehicle['id']) ? $vehicle['id'] : '')."</id>
                                                <group>".(isset($vehicle['group']) ? $vehicle['group'] : '')."</group>
                                            </vehicle>
                                            <pickup>
                                                <location>".$pickUpLocationCode."</location>
                                                <date>".$pickUpDate."</date>
                                                <time>".$pickUpTime."</time>
                                            </pickup>
                                            <dropoff>
                                                <location>".$dropOffLocationCode."</location>
                                                <date>".$dropOffDate."</date>
                                                <time>".$dropOffTime."</time>
                                            </dropoff>
                                            <days>".(isset($vehicle['hiredays']) ? $vehicle['hiredays'] : '')."</days>
                                            <price>".(isset($vehicle['price']) ? $vehicle['price'] : '')."</price>
                                            <currency>".(isset($vehicle['currency']) ? $vehicle['currency'] : '')."</currency>
                                        </reservation>
                                    </MakeReservation>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else if ($response) {
//                self::convertXMLResponseToFile($response);
            return self::convertXMLResponseToArray($response);
//                Utility::sendTestEmail($data);
        } else {
          echo "Unknown Error";  
        }
    }
}
