<?php
namespace App\Http\Controllers\Model;
use Illuminate\Database\Capsule\Manager as DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use EB\Observers\ClientSettingObserver;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of BaseModelController
 *
 * @author apple
 */
class BaseModelController {
    
    public static function getLocations() {
        $data = \App\Models\LocationsModel::select('locations.id','name', 'locations.code','locations.location_id','country_name', 'country_code', 'currency')
        ->JoinCountry()
        ->with(['Vendors'=> function($q) {
                $q->select('vendors.id', 'vendor_location_map.location_id','vendor_location_map.vendor_id','vendors.company_name', 'vendors.api_url_code', 'vendors.is_api_enable')->JoinVendors();
        }]);
        $data->where('locations.status',1);
        $data = $data->orderBy('name','asc')->get()->toArray();
        return $data;
    }
    public static function getLocationById($id){
        $data = \App\Models\LocationsModel::select('locations.id','name', 'locations.code','locations.location_id','country_name', 'country_code', 'currency')
        ->JoinCountry()
        ->with(['Vendors'=> function($q) {
                $q->select('vendors.id', 'vendor_location_map.location_id','vendor_location_map.vendor_id','vendors.company_name', 'vendors.api_url_code', 'vendors.is_api_enable')->JoinVendors();
        }]);
        $data->where('locations.status',1);
        $data->where('locations.id', $id);
        $data = $data->first();
        return isset($data) ? $data->toArray() : [];
    }
    public static function getCountry(){
        return \App\Models\CountryModel::select('id','country_name','phone_code')->where('status',1)->orderBy('country_name','asc')->get()->toArray();
    }
    public static function getLocationWithName($name){
        $data = \App\Models\LocationsModel::select('locations.id','name', 'locations.code','locations.location_id','country_name', 'country_code', 'currency')
        ->JoinCountry();
        $data->where('locations.status',1);
        $data->where(function ($q2) use($name) {
            $q2->where('name', 'like', '%'.$name.'%')
            ->orWhere("country_name", "like", '%'.$name.'%');
        });
        $data = $data->get();
        return isset($data) ? json_encode($data->toArray()) : [];
    }
    public static function getVendors(){
        return \App\Models\VendorsModel::select('id','company_name')->where('status',1)->orderBy('company_name','asc')->get()->toArray(); 
    }
    public static function getVendorsWithIds($ids){
        return \App\Models\VendorsModel::select('id','company_name','api_url_code','is_api_enable')->whereIn('id',$ids)->where('status',1)->orderBy('company_name','asc')->get()->toArray(); 
    }
    public static function getMenu($menu_id = 0){
        return \App\Models\Menu::where('menu_id', $menu_id)->where('status', '1')->get()->toArray();
    }
    public static function getMenuByLink($link){
        return \App\Models\Menu::where('menu_link', $link)->where('status', '1')->get()->toArray();
    }
    public static function getCurrency(){
        //$currency = \App\Models\CountryModel::select('id','currency_name', 'currency', 'symbol')->whereNotNull('currency')->orderBy('currency_name','asc')->get()->toArray();
        $currency = [];
        return $currency;
    }
    public static function getLanguage(){
       // $currency = \App\Models\LanguageModel::select('id','name', 'code')->where('is_active', 1)->orderBy('name','asc')->get()->toArray();
       $currency = ''; 
       return $currency;
    }
    public static function getDynamicLanguage($language_code){
        // $language = \App\Models\LanguageModel::select('id','name', 'code')->where('is_active', 1)->where('code', $language_code)->first();
        // if(isset($language) && !empty($language)){
        //     return $language_code;
        // }
        return 'en';
    }
    public static function getDynamicCurrency($currency_code){
        // $currency = \App\Models\CountryModel::select('id')->where('status', 1)->where('currency', $currency_code)->first();
        // if(isset($currency) && !empty($currency)){
        //     return $currency_code;
        // }
        return 'USD';
    }
    public static function getDynamicCurrencyFromCountryCode($country_code){
        // $currency = \App\Models\CountryModel::select('id', 'currency')->where('status', 1)->where('country_code', $country_code)->first();
        // if(isset($currency) && !empty($currency)){
        //     return $currency->currency;
        // }
        return 'USD';
    }
    public function setCurrency($currency){
        session()->put('currency_name', $currency);
        $conversion_currency_name = session()->get('conversion_currency_name');
        if(isset($conversion_currency_name) && $conversion_currency_name != $currency){
            $getCurrency = self::getCurrencyValue($conversion_currency_name, $currency);
            session()->put('currency_value', $getCurrency);
        } else {
            session()->put('currency_value', 1);
        }
        return ['currency_from'=>$conversion_currency_name, 'currency_to' => $currency, 'conversion_value' => isset($getCurrency) ? $getCurrency : 1];
    }
    public function setLanguage($language){
        session()->put('language_code', $language);
        return true;
    }
    public static function getIpstackData(){
        $ip = \request()->ip();
        $url = 'https://api.ipstack.com/'.$ip.'?access_key='.env('IPSTACK');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        //$currency = self::getDynamicCurrency(((isset($response->currency->code)) ? $response->currency->code : 'USD'));
        $currency = self::getDynamicCurrencyFromCountryCode(((isset($response->country_code)) ? $response->country_code : 'US'));
        $language = self::getDynamicLanguage((isset($response->location->languages[0]->code) ? $response->location->languages[0]->code : 'en'));
        session()->put('language_code', $language);
        session()->put('currency_name', $currency);
        if($language != 'en'){
            setcookie('googtrans', "/en/".$language, time() + (86400 * 30), "/");  
        }
    }
    public static function getCurrencyValue($from, $to){
        $curl = curl_init();
        $key = env('FASTFOREX');
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fastforex.io/fetch-one?from='.$from.'&to='.$to.'&api_key='.$key,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responce = json_decode($response);
        return $responce->result->$to;
    }
}