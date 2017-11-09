<?php 
/** 
@author heting
*/

require_once 'config.php';

require_once '../17monipdb/IP.class.php';
require '../vendor/autoload.php';

use GeoIp2\Database\Reader;

$reader = new Reader(dirname(dirname(__FILE__)).'/public/GeoLite2-City.mmdb');

try{
	$data = $database->select("event", [
		"[>]iphdr" => ["cid" => "cid"],
		"[>]signature" => ["signature" => "sig_id"],
		"[>]sig_class" => ["signature.sig_class_id" => "sig_class_id"]
	],[
	'event.cid',
	'event.timestamp',
	'iphdr.ip_src',
	'iphdr.ip_dst',
	'sig_class.sig_class_name',
	'sig_class.class'
	],[
	'ORDER'=>['event.cid'=>'DESC']
	]);

	foreach ($data as $key => $val) {
		$data[$key]['ip_src'] = long2ip($val['ip_src']);
		$data[$key]['ip_dst'] = long2ip($val['ip_src']);
		
		$data[$key]['src'] = getLocationByip(long2ip($val['ip_src']));
		$data[$key]['dst'] = getLocationByip(long2ip($val['ip_dst']));
	}

	echo json_encode([
		'status' => 0,
		'data' => $data,
	]);
}catch(Exception $err){
	echo json_encode([
	'status' => -1,
	'msg' => $err
	]);
}


function getLocationByip($ip) {
	global $reader;
	$cityArr = array_filter(IP::find($ip));
	
	if(count($cityArr)){
		switch ($cityArr[0]) {
			case '局域网':
				return ['city'=>'局域网','ip'=>$ip];
				break;
			case '保留地址':
				return ['city'=>'保留地址','ip'=>$ip];
				break;
			case 'IPIP.NET':
				return ['city'=>'','ip'=>$ip];
				break;
			default:
				$record = $reader->city($ip);
				if($record->city) {
					$res = array(
						'country' => $record->country->names['zh-CN'],
						'city' => $record->city->names['zh-CN'],
						'ip' => $ip,
						'longitude' => $record->location->longitude,
						'latitude' => $record->location->latitude);
					return $res;
				} else {
					return ['city'=>'','ip'=>$ip];
				}
				
		}		
	}

}

?>