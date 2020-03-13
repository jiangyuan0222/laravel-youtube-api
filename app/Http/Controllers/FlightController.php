<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Flight;
use Illuminate\Support\Facades\Storage;

class FlightController extends Controller
{
    public function getAllAirports()
    {
        $data = Flight::getData();
        return response()->json(['status'=> 200, 'data' => $data], 200);
    } 
    

    public function insertCsv()
    {
        $file_n = storage_path('/app/airport-long-lat-country.csv'); 
        $file = fopen($file_n, "r");
        $all_data = array();
        
        $json_array = "var airports1 = [";
        while( (($data = fgetcsv($file, 200, ","))) !== FALSE) {
            $code = $data[0];
            $lat_minutes = explode(";", $data[1]);
            $lon_minutes = explode(";", $data[2]);
            $country = $data[3];

            $lat_min = round($lat_minutes[1] / 60, 4);
            $lon_min = round($lon_minutes[1] / 60, 4);
            // print_r($minutes);
            // echo $minute;
            $case = substr($data[1], 0, 1).substr($data[2], 0, 1);
            $sign = "";
            switch ($case) {
                case 'NE':
                    $lat = (float)substr($data[1], 1, strpos($data[1], ';')-1) + $lat_min;
                    $lon = (float)substr($data[2], 1, strpos($data[2], ';')-1) + $lon_min;
                    break;
                case 'SE':
                    $lat = (float)substr($data[1], 1, strpos($data[1], ';')-1) * (-1) - $lat_min;
                    $lon = (float)substr($data[2], 1, strpos($data[2], ';')-1) + $lon_min;
                    break;
                case 'SW':
                    $lat = (float)substr($data[1], 1, strpos($data[1], ';')-1) * (-1) - $lat_min;
                    $lon = (float)substr($data[2], 1, strpos($data[2], ';')-1) * (-1) - $lon_min;
                    break;
                default:    // NW
                    $lat = (float)substr($data[1], 1, strpos($data[1], ';')-1) + $lat_min;
                    $lon = (float)substr($data[2], 1, strpos($data[2], ';')-1) * (-1) - $lon_min;
                    break;
            } 

            $json_array .= "{'direct_flights':'50','code':'".$code."','lon':'".$lon."','lat':'".$lat."', 'country': '".$country."'},";
        }

        $json_array .= "];";
        Storage::put('airport3.js', $json_array);
        
        fclose($file);
        return response()->json(['status'=> 200, 'data' => 'success'], 200);
    }

    // {
    //     "date": "2020-01-19",
    //     "airline": "DD",
    //     "airport1": "ATL",
    //     "airport2": "LGA",
    //     "dep_time": "10:00",
    //     "arr_time": "11:30",
    //     "flight_num": "111",
    //     "cnt": "112"
    // },
    public function insertFlight()
    {
        $file_n = storage_path('/app/flightsdetailsmall_formatted.csv'); 
        $file = fopen($file_n, "r");
        $all_data = array();
        
        $json_array = "var flight1 = [";
        while( (($data = fgetcsv($file, 200, ","))) !== FALSE) {
            $date = $data[0];
            $airport1 = $data[1];
            $airport2 = $data[2];
            $dep_time = $data[3];
            $arr_time = $data[4];
            $airline = $data[5];
            $flight_num = $data[6];
            $country = $data[7];
            $cnt = "112";
            
            $json_array .= "{'date':'".$date."','airport1':'".$airport1."','airport2':'".$airport2."','dep_time':'".$dep_time."', 'arr_time': '".$arr_time."', 'airline': '".$airline."', 'flight_num': '".$flight_num."', 'country': '".$country."', 'cnt': '".$cnt."'},";
        }

        $json_array .= "];";
        Storage::put('flight1.js', $json_array);
        
        fclose($file);
        return response()->json(['status'=> 200, 'data' => 'success'], 200);
    }

    public function getCentralPoint() {
        $file_n = storage_path('/app/central point.csv'); 
        $file = fopen($file_n, "r");
        $all_data = array();
        
        $json_array = "var centrals = [";
        while( (($data = fgetcsv($file, 200, ","))) !== FALSE) {
            $code = $data[0];
            $lat = $data[1];
            $long = $data[2];
            $name = $data[3];
            echo $code."<br>";
            $json_array .= "{'code':'".$code."','lat':'".$lat."','long':'".$long."','name':'".$name."'},";
        }

        $json_array .= "];";
        Storage::put('centrals.js', $json_array);
        
        fclose($file);
        return response()->json(['status'=> 200, 'data' => 'success'], 200);
    }

    // public function insertCsv_temp()
    // {
    //     $file_n = storage_path('/app/LatLongClean.csv'); //Storage::url('LatLongClean.csv');
    //     $file_t = storage_path('/app/LatLongClean_out.csv'); //Storage::url('LatLongClean.csv');
    //     $file = fopen($file_n, "r");
    //     $file_out = fopen($file_t, "a");
    //     $all_data = array();
        
    //     $sql = "insert  into `flights`(`code`,`lon`,`lat`) values ";
        
    //     $json_array = "[";
    //     while( (($data = fgetcsv($file, 200, ","))) !== FALSE) {
    //         $Flight = new Flight();
    //         $code = $data[0];
    //         $lon = substr($data[1], 1, strpos($data[1], ';')-1);
    //         $lat = "-".substr($data[2], 1, strpos($data[2], ';')-1);
    //         $array = array();
    //         $Flight->code = $array['code'] = $code;
    //         $Flight->lon = $array['lon'] = $lon;
    //         $Flight->lat = $array['lat'] = $lat;
    //         // $Flight->save();
            
    //         $sql .= "('".$code."',".$lon.",".$lat."),";
            
    //         $input = array(
    //             'direct_flights'=>100,
    //             'code'=>$code,
    //             'lon'=>$lon,
    //             'lat'=>$lat 
    //         );

    //         $json_array .= ""

    //         fputcsv($file_out, $input);

    //         array_push($all_data, $input);
    //     }
        
        
    //     fclose($file);
    //     return response()->json(['status'=> 200, 'data' => $sql], 200);
    // }
}
