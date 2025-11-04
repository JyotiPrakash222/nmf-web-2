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
class YesAwayApiController extends BaseApiController {

    private static $baseUrl = "http://javelin-api.yesaway.com/services";
    private static $requestTimeout = 60;

    private static $maxApiResponse = 50;
    private static $apiEnvironment = "Production";
    private static $apiVersion = "3.0";
    private static $apiCompanyName = "car4hires";
    private static $apiCompanyShortName = "car4hires";
    private static $apiCurrencyModel;
    private static $apiCurrency;
    private static $apiCode = "yesaway"; // Which we need to capture from DB
    private static $apiRequestorsMapping = [
        'US' => 'W_US_ORDER_COM_ARRIVAL',
        'NZ' => 'W_NZ_ORDER_COM_ARRIVAL',
        'TH' => 'W_TH_ORDER_COM_ARRIVAL',
        'AUS' => 'W_AU_ORDER_COM_ARRIVAL',
        'United States' => 'W_US_ORDER_COM_ARRIVAL',
        'New Zealand' => 'W_NZ_ORDER_COM_ARRIVAL',
        'Thailand' => 'W_TH_ORDER_COM_ARRIVAL',
        'Australia' => 'W_AU_ORDER_COM_ARRIVAL',
    ];
    
    
    public static function createBookingArray($data){
        $cars_data = json_decode($data['cars'], true);
        $data['AirConditionInd'] = isset($cars_data['VehAvailCore']['Vehicle']['@attributes']['AirConditionInd']) ? $cars_data['VehAvailCore']['Vehicle']['@attributes']['AirConditionInd'] : '';
        $data['VehicleCategory'] = isset($cars_data['VehAvailCore']['Vehicle']['VehType']['@attributes']['VehicleCategory']) ? $cars_data['VehAvailCore']['Vehicle']['VehType']['@attributes']['VehicleCategory'] : '';
        $data['Size'] = isset($cars_data['VehAvailCore']['Vehicle']['VehClass']['@attributes']['Size']) ? $cars_data['VehAvailCore']['Vehicle']['VehClass']['@attributes']['Size'] : '';
        $data['Code'] = isset($cars_data['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Code']) ? $cars_data['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Code'] : '';
        $data['TransmissionType'] = isset($cars_data['VehAvailCore']['Vehicle']['@attributes']['TransmissionType']) ? $cars_data['VehAvailCore']['Vehicle']['@attributes']['TransmissionType'] : "";
        $data['VehGroupID'] = isset($cars_data['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['VehGroupID']) ? $cars_data['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['VehGroupID'] : '';  
        $data['company_name']= $cars_data['company_name'];
        $data['api_url_code']= $cars_data['api_url_code'];
        $data['currency'] = session()->get('currency_name');
        $data['vehicle_actual_currency'] = $cars_data['vehicle_price_currency'];
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
        $data['vehicle']['AirConditionInd'] = $booking['AirConditionInd'];
        $data['vehicle']['VehicleCategory'] = $booking['VehicleCategory'];
        $data['vehicle']['Size'] = $booking['Size'];
        $data['vehicle']['Code'] = $booking['Code'];
        $data['vehicle']['TransmissionType'] = $booking['TransmissionType'];
        $data['vehicle']['VehGroupID'] = $booking['VehGroupID'];
        $data['user']['prefix'] = '';
        $data['user']['first_name'] = $personal_detail['firstname'];
        $data['user']['last_name'] = $personal_detail['lasttname'];
        $data['user']['phone_code'] = $personal_detail['mobile_code'];
        $data['user']['phone'] = $personal_detail['mobile_no'];
        $data['user']['email'] = $personal_detail['email'];
        $data['pickup_datetime'] = date('Y-m-d', strtotime($booking['start_date']))."T".date('H:i:s', strtotime($booking['start_time']));
        $data['dropoff_datetime'] = date('Y-m-d', strtotime($booking['end_date']))."T".date('H:i:s', strtotime($booking['end_time']));
        $data['pickup_location_code'] = isset($pickup['code']) ? $pickup['code'] : '';
        $data['dropoff_location_code'] = isset($dropoff['code']) ? $dropoff['code'] : '';
        $data['booking_id'] = isset($booking['id']) ? $booking['id'] : '';
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
    
    
    public static function getLocations($is_download = 0, $is_json_string = 0) {
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
                                    <soap:Envelope
                                            xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"
                                            xmlns:ns=\"http://www.opentravel.org/OTA/2003/05\">  
                                            <soap:Body>    
                                                    <OTA_VehLocSearchRQ
                                                            xmlns=\"http://www.opentravel.org/OTA/2003/05\"
                                                            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" PrimaryLangID=\"EN\" MaxResponses='".self::$maxApiResponse."' Target='".self::$apiEnvironment."' Version='".self::$apiVersion."' TransactionIdentifier=\"100000002\" xsi:schemaLocation=\"http://www.opentravel.org/OTA/2003/05\">    
                                                            <POS>        
                                                                    <Source ISOCountry=\"\">          
                                                                            <RequestorID Type=\"4\">            
                                                                                    <CompanyName Code='".self::$apiCompanyName."' CompanyShortName='".self::$apiCompanyShortName."'/>          
                                                                            </RequestorID>        
                                                                    </Source>        
                                                                    <Source>          
                                                                            <RequestorID Type=\"4\" ID=\"00000000\" ID_Context=\"IATA\"/>        
                                                                    </Source>    
                                                            </POS>    
                                                            <VehLocSearchCriterion>
                                                                    <Location Code=\"\"/>
                                                            </VehLocSearchCriterion>    
                                                            <Vendor Code=\"yesaway\"/>
                                                    </OTA_VehLocSearchRQ> 
                                            </soap:Body>
                                    </soap:Envelope>",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic Y2FyNGhpcmVzOmMwOWE4NzJkZjVhZWQ5ZTM3YTg4Zjc1NDdmODU5NWUx",
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

        /**
     * 
     * @param type $locationCode : This is to search for particular area like HKT01
     */
    public static function getLocation($locationCode)
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
                                    <soap:Envelope
                                            xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"
                                            xmlns:ns=\"http://www.opentravel.org/OTA/2003/05\">  
                                            <soap:Body>    
                                                    <OTA_VehLocSearchRQ
                                                            xmlns=\"http://www.opentravel.org/OTA/2003/05\"
                                                            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" PrimaryLangID=\"EN\" MaxResponses='".self::$maxApiResponse."' Target='".self::$apiEnvironment."' Version='".self::$apiVersion."' TransactionIdentifier=\"100000002\" xsi:schemaLocation=\"http://www.opentravel.org/OTA/2003/05\">    
                                                            <POS>        
                                                                    <Source ISOCountry=\"\">          
                                                                            <RequestorID Type=\"4\">            
                                                                                    <CompanyName Code='".self::$apiCompanyName."' CompanyShortName='".self::$apiCompanyShortName."'/>          
                                                                            </RequestorID>        
                                                                    </Source>        
                                                                    <Source>          
                                                                            <RequestorID Type=\"4\" ID=\"00000000\" ID_Context=\"IATA\"/>        
                                                                    </Source>    
                                                            </POS>    
                                                            <VehLocSearchCriterion>
                                                                    <Location Code='".$locationCode."'/>
                                                            </VehLocSearchCriterion>    
                                                            <Vendor Code=\"yesaway\"/>
                                                    </OTA_VehLocSearchRQ> 
                                            </soap:Body>
                                    </soap:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic Y2FyNGhpcmVzOmMwOWE4NzJkZjVhZWQ5ZTM3YTg4Zjc1NDdmODU5NWUx",
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
            self::convertXMLResponseToExcel($response);
        } else {
          echo "Unknown Error";  
        }
    }

   
    public static function searchVehiclesForLocationTest(Request $request)
    {
        $input = $request->input();
        $countryCode = isset($input['country_code']) ? $input['country_code'] : 'US';        
        if(isset(self::$apiRequestorsMapping[$countryCode]) && !empty(self::$apiRequestorsMapping[$countryCode])) {
            $pickUpDateTime = isset($input['pickup_datetime']) ? $input['pickup_datetime'] : '';
            $dropOffDateTime = isset($input['dropoff_datetime']) ? $input['dropoff_datetime'] : '';
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
              CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns=\"http://www.opentravel.org/OTA/2003/05\">\n  <soap:Body>\n    <OTA_VehAvailRateMoreRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" PrimaryLangID=\"EN\" MaxResponses=\"50\" Target=\"Production\" Version=\"3.0\" TransactionIdentifier=\"100000002\" xsi:schemaLocation=\"http://www.opentravel.org/OTA/2003/05\">\n      <POS>\n        <Source ISOCountry=\"TH\">\n          <RequestorID Type=\"4\" ID=\"W_TH_ORDER_COM_ARRIVAL\">\n            <CompanyName Code=\"car4hires\" CompanyShortName=\"car4hires\"/>\n          </RequestorID>\n        </Source>\n        <Source>\n          <RequestorID Type=\"4\" ID=\"00000000\" ID_Context=\"IATA\"/>\n        </Source>\n      </POS>\n      <VehAvailRQCore Status=\"Available\">\n        <VehRentalCore PickUpDateTime=\"2024-09-22T16:00:00\" ReturnDateTime=\"2024-09-29T10:00:00\">\n          <PickUpLocation LocationCode=\"HKT01\" PointCode=\"1\"/>\n          <ReturnLocation LocationCode=\"HKT01\" PointCode=\"1\"/>\n        </VehRentalCore>\n        <VendorPrefs>\n          <VendorPref Code=\"yesaway\"/>\n        </VendorPrefs>\n        <DriverType Age=\"150\"/>\n        <TPA_Extensions>\n          <TPA_Extension_Flags EnhancedTotalPrice=\"true\"/>\n        </TPA_Extensions>\n      </VehAvailRQCore>\n    </OTA_VehAvailRateMoreRQ>\n  </soap:Body>\n</soap:Envelope>",
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y2FyNGhpcmVzOmMwOWE4NzJkZjVhZWQ5ZTM3YTg4Zjc1NDdmODU5NWUx",
                "cache-control: no-cache",
                "content-type: text/xml",
              ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
             return "cURL Error #:" . $err;
            } else if ($response) {
//                self::convertXMLResponseToFile($response);
                $data = self::convertXMLResponseToArray($response);
                return response()->json(self::formatVehiclesForLocationData($data));
            } else {
              return "Unknown Error";  
            }
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
        $countryCode = isset($input['country_code']) ? $input['country_code'] : '';        
        if(isset(self::$apiRequestorsMapping[$countryCode]) && !empty(self::$apiRequestorsMapping[$countryCode])) {
            $pickUpDateTime = isset($input['pickup_datetime']) ? $input['pickup_datetime'] : '';
            $dropOffDateTime = isset($input['dropoff_datetime']) ? $input['dropoff_datetime'] : '';
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
                                        <soap:Envelope
                                                xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"
                                                xmlns:ns=\"http://www.opentravel.org/OTA/2003/05\">
                                                <soap:Body>
                                                        <OTA_VehAvailRateMoreRQ
                                                                xmlns=\"http://www.opentravel.org/OTA/2003/05\"
                                                                xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" PrimaryLangID=\"EN\" MaxResponses='".self::$maxApiResponse."' Target='".self::$apiEnvironment."' Version='".self::$apiVersion."' TransactionIdentifier=\"100000002\" xsi:schemaLocation=\"http://www.opentravel.org/OTA/2003/05\">
                                                                <POS>
                                                                        <Source ISOCountry='".$countryCode."'>
                                                                                <RequestorID Type=\"4\" ID='".self::$apiRequestorsMapping[$countryCode]."'>
                                                                                        <CompanyName Code='".self::$apiCompanyName."' CompanyShortName='".self::$apiCompanyShortName."'/>          
                                                                                </RequestorID>
                                                                        </Source>
                                                                        <Source>
                                                                                <RequestorID Type=\"4\" ID=\"00000000\" ID_Context=\"IATA\"/>
                                                                        </Source>
                                                                </POS>
                                                                <VehAvailRQCore Status=\"Available\">
                                                                        <VehRentalCore PickUpDateTime='".$pickUpDateTime."' ReturnDateTime='".$dropOffDateTime."'>
                                                                                <PickUpLocation LocationCode='".$pickUpLocationCode."' PointCode=\"1\"/>
                                                                                <ReturnLocation LocationCode='".$dropOffLocationCode."' PointCode=\"1\"/>
                                                                        </VehRentalCore>
                                                                        <VendorPrefs>
                                                                                <VendorPref Code='".self::$apiCode."'/>
                                                                        </VendorPrefs>
                                                                        <DriverType Age=\"\"/>
                                                                        <TPA_Extensions>
                                                                                <TPA_Extension_Flags EnhancedTotalPrice=\"true\"/>
                                                                        </TPA_Extensions>
                                                                </VehAvailRQCore>
                                                        </OTA_VehAvailRateMoreRQ>
                                                </soap:Body>
                                        </soap:Envelope>",
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y2FyNGhpcmVzOmMwOWE4NzJkZjVhZWQ5ZTM3YTg4Zjc1NDdmODU5NWUx",
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
        else {
            return response()->json(['message' => 'Please input valid country code', 'status' => 0], 200);
        }
    }
    
    private static function formatVehiclesForLocationData($data, $vendor_id = 1) {
        if(isset($data['OTA_VehAvailRateMoreRS']['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail']) && is_array($data['OTA_VehAvailRateMoreRS']['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail']) && !empty($data['OTA_VehAvailRateMoreRS']['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail'])) {            
            $vehicles_data = $data['OTA_VehAvailRateMoreRS']['VehAvailRSCore']['VehVendorAvails']['VehVendorAvail']['VehAvails']['VehAvail'];
            $responseData['vehicles'] = [];
            $conversionAmount = NULL;
            $commission = BaseApiController::getCommission($vendor_id);
            foreach($vehicles_data as $object) {
                $object['vehicle_name'] = (isset($object['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Name']) && !empty($object['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Name'])) ? $object['VehAvailCore']['Vehicle']['VehMakeModel']['@attributes']['Name'] : '';
                $object['vehicle_image'] = (isset($object['VehAvailCore']['Vehicle']['PictureURL']) && !empty($object['VehAvailCore']['Vehicle']['PictureURL'])) ? ((is_array($object['VehAvailCore']['Vehicle']['PictureURL']) && count($object['VehAvailCore']['Vehicle']['PictureURL']) > 0) ? (isset($object['VehAvailCore']['Vehicle']['PictureURL'][0]) ? $object['VehAvailCore']['Vehicle']['PictureURL'][0] : $object['VehAvailCore']['Vehicle']['PictureURL']) : $object['VehAvailCore']['Vehicle']['PictureURL'])  : '';
                
                $object['others'] = [];
                $airConditionInd = isset($object['VehAvailCore']['Vehicle']['@attributes']['AirConditionInd']) ? $object['VehAvailCore']['Vehicle']['@attributes']['AirConditionInd'] : '';
                if($airConditionInd == 'true') {
                    $object['others'][] = 'Air Conditioning';
                }
                $transmissionType = isset($object['VehAvailCore']['Vehicle']['@attributes']['TransmissionType']) ? $object['VehAvailCore']['Vehicle']['@attributes']['TransmissionType'] : "";
                if($transmissionType == 'Automatic') {
                    $object['others'][] = 'Automatic Transmission';
                }
                $object['passenger'] = isset($object['VehAvailCore']['Vehicle']['@attributes']['PassengerQuantity']) ? $object['VehAvailCore']['Vehicle']['@attributes']['PassengerQuantity'] : "";
                $object['bag'] = isset($object['VehAvailCore']['Vehicle']['@attributes']['BaggageQuantity']) ? $object['VehAvailCore']['Vehicle']['@attributes']['BaggageQuantity'] : "";
                $object['door'] = isset($object['VehAvailCore']['Vehicle']['VehType']['@attributes']['DoorCount']) ? $object['VehAvailCore']['Vehicle']['VehType']['@attributes']['DoorCount'] : '';
                               
                $object['vehicle_actual_price'] = (isset($object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['Amount']) && !empty($object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['Amount'])) ? $object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['Amount'] : '';
                $object['vehicle_price'] = BaseApiController::getVehiclePriceWithCommission($commission, $object['vehicle_actual_price']);
                $object['vehicle_price_currency'] = (isset($object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['CurrencyCode']) && !empty($object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['CurrencyCode'])) ? $object['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['CurrencyCode'] : self::$apiCurrency;
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
        $vehicle_price_currency = isset($input['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['CurrencyCode']) ? $input['VehAvailInfo']['PricedCoverages']['PricedCoverage'][0]['Charge']['@attributes']['CurrencyCode'] : '';
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
    
    public static function bookVehicleForLocation($input)
    {
        $countryCode = isset($input['country_code']) ? $input['country_code'] : '';        
        if(isset(self::$apiRequestorsMapping[$countryCode]) && !empty(self::$apiRequestorsMapping[$countryCode])) {
            $pickUpDateTime = isset($input['pickup_datetime']) ? $input['pickup_datetime'] : '';
            $dropOffDateTime = isset($input['dropoff_datetime']) ? $input['dropoff_datetime'] : '';
            $pickUpLocationCode = isset($input['pickup_location_code']) ? $input['pickup_location_code'] : '';
            $dropOffLocationCode = isset($input['dropoff_location_code']) ? $input['dropoff_location_code'] : '';
            $bookingId = isset($input['booking_id']) ? $input['booking_id'] : '';
            $bookingTime = strtotime(date("d-m-Y H:i:s"));
            $driverAge = isset($input['driver_age']) ? $input['driver_age'] : '35';
            $driverLicenseType = isset($input['driver_license_type']) ? $input['driver_license_type'] : '2';
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
                                        <soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns=\"http://www.opentravel.org/OTA/2003/05\">
                                            <soap:Body>
                                                <OTA_VehResRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\"
                                                              xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" Target=\"Production\" Version=\"3.0\"
                                                              TransactionIdentifier=\"100000002\" xsi:schemaLocation=\"http://www.opentravel.org/OTA/2003/05\">
                                                    <POS>
                                                        <Source ISOCountry='".$countryCode."'>
                                                            <RequestorID Type=\"4\" ID='".self::$apiRequestorsMapping[$countryCode]."'>
                                                                <CompanyName Code='".self::$apiCompanyName."' CompanyShortName='".self::$apiCompanyShortName."'/> 
                                                            </RequestorID>
                                                        </Source>
                                                        <Source>
                                                            <RequestorID Type=\"4\" ID=\"00000000\" ID_Context=\"IATA\"/>
                                                        </Source>
                                                    </POS>
                                                    <VehResRQCore Status=\"Available\">
                                                        <RateQualifier RateQualifier='".self::$apiRequestorsMapping[$countryCode]."'/>
                                                        <VehRentalCore PickUpDateTime='".$pickUpDateTime."' ReturnDateTime='".$dropOffDateTime."'>
                                                            <PickUpLocation LocationCode='".$pickUpLocationCode."'/>
                                                            <ReturnLocation LocationCode='".$dropOffLocationCode."'/>
                                                        </VehRentalCore>
                                                        <Customer>
                                                            <Primary>
                                                                <PersonName>
                                                                    <NamePrefix>".(isset($user['prefix']) ? $user['prefix'] : '')."</NamePrefix>
                                                                    <GivenName>".(isset($user['first_name']) ? $user['first_name'] : '')."</GivenName>
                                                                    <Surname>".(isset($user['last_name']) ? $user['last_name'] : '')."</Surname>
                                                                    <NameSuffix/>
                                                                </PersonName>
                                                                <Telephone PhoneUseType=\"3\" AreaCityCode='".(isset($user['phone_code']) ? $user['phone_code'] : '')."' PhoneNumber='".(isset($user['phone']) ? $user['phone'] : '')."'/>
                                                                <Email>".(isset($user['email']) ? $user['email'] : '')."</Email>
                                                                <Address>
                                                                    <CountryName Code='".$countryCode."'/>
                                                                </Address>
                                                            </Primary>
                                                        </Customer>
                                                        <VehPref AirConditionInd='".(isset($vehicle['AirConditionInd']) ? $vehicle['AirConditionInd'] : 'true')."' TransmissionType='".(isset($vehicle['TransmissionType']) ? $vehicle['TransmissionType'] : 'Automatic')."' Code='".(isset($vehicle['Code']) ? $vehicle['Code'] : '')."' VehGroupID='".(isset($vehicle['VehGroupID']) ? $vehicle['VehGroupID'] : '')."'>
                                                            <VehType VehicleCategory='".(isset($vehicle['VehicleCategory']) ? $vehicle['VehicleCategory'] : '2')."'/>
                                                            <VehClass Size='".(isset($vehicle['Size']) ? $vehicle['Size'] : '7')."'/>
                                                        </VehPref>
                                                        <EasyRent>false</EasyRent>
                                                        <DriverType Age='".$driverAge."' DriverLicenseType='".$driverLicenseType."'/>
                                                        <VendorPref Code='".self::$apiCode."'/>
                                                        <TPA_Extensions>
                                                            <TPA_Extension_Flags EnhancedTotalPrice=\"true\"/>
                                                            <TPA_Extension_Remark><![CDATA[remark text]]></TPA_Extension_Remark>
                                                        </TPA_Extensions>
                                                    </VehResRQCore>
                                                    <VehResRQInfo>
                                                        <ArrivalDetails TransportationCode=\"14\" Number=\"\">
                                                            <OperatingCompany Code=\"\"/>
                                                        </ArrivalDetails>
                                                        <RentalPaymentPref>
                                                            <Voucher SeriesCode='".self::$apiCompanyName.'-'.$bookingId.'-'.$bookingTime."'/>
                                                        </RentalPaymentPref>
                                                    </VehResRQInfo>
                                                </OTA_VehResRQ>
                                            </soap:Body>
                                        </soap:Envelope>",
              CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y2FyNGhpcmVzOmMwOWE4NzJkZjVhZWQ5ZTM3YTg4Zjc1NDdmODU5NWUx",
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
//                return self::convertXMLResponseToArray($response);
//                Utility::sendTestEmail($data);
            return self::convertXMLResponseToArray($response);
            } else {
              echo "Unknown Error";  
            }
        } 
        else {
            return response()->json(['message' => 'Please input valid country code', 'status' => 0], 200);
        }
    }
}
