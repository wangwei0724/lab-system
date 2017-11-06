<?php 
/** 
@author heting
*/

require_once 'config.php';

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
	'sig_class.sig_class_name'
	],[
	'ORDER'=>['event.cid'=>'DESC']
	]);

	foreach ($data as $key => $val) {
		$data[$key]['ip_src'] = long2ip($val['ip_src']);
		$data[$key]['ip_dst'] = long2ip($val['ip_src']);		
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


?>