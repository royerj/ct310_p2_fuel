<?php
use Model\Ormattraction;
class Controller_Federation extends Controller {
    
    /*
    * status
    */
    
    public function action_status(){
        $array = array('status' => 'open');
        return Format::forge($array)->to_json();
    }
    
    
    public function action_allstatus(){
        //$var = "test.txt"::find('all');
        //return $var;
        
        $xml = file_get_contents("https://www.cs.colostate.edu/~ct310/yr2018sp/master.json");
        $array = json_decode($xml);
        print_r($array);
        /*$zack = array();
        
        foreach($array as $arr){
            $nameShort = $arr["nameShort"];
            $nameLong = $arr["nameLong"];
            $eid = $arr["eid"];
            
            $url = "http://www.cs.colostate.edu/~$eid/ct310/index.php/federation/status";
            $stat = json_decode($url);
            
            $zack = [$nameShort, $nameLong, $eid, $stat["status"]];
            
            if($stat["status"] == "open")
                $stat = "statusOpen";
                
            else
                $stat = "statusClosed";
                
        }*/
        
        //return $array;
        
    }
    
    public function action_listing(){

		$attractions = Ormattraction::find('all', array('select' =>array ('attractionID', 'name', 'state')));
		$dataArray = array();
		foreach($attractions as $i){
			$temp = array();
			$temp['id']  = $i['attractionID'];
			$temp['name']  = $i['name'];
			$temp['state']  = $i['state'];
			$dataArray[] = $temp;
		}
		
		return Format::forge($dataArray)->to_json();
    }
        
    public function action_attraction($id){
		
		$attractions = Ormattraction::find($id, array('select' =>array ('attractionID', 'name','details', 'state')));
		$dataArray = array();
			$temp = array();
			$temp['id']  = $attractions['attractionID'];
			$temp['name']  = $attractions['name'];
			$temp['desc']  = $attractions['details'];
			$temp['state']  = $attractions['state'];
			$dataArray[] = $temp;
		
		return Format::forge($dataArray)->to_json();
	}

    public function action_attrimage($id){
		
		$image = Ormattraction::find($id, array('select' =>array ('img')));
		$response = Response::forge(file_get_contents(Asset::get_file($image['img'], 'img')));
		
		$response->set_header('Content-Type', 'image/jpeg');	
		
		
		return $response;
	}
}
        
