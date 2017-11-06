<?php 
/** 
@author heting
*/

require_once 'config.php';

try{
	$data = $database->select("event", [
		"[>]signature" => ["signature" => "sig_id"],
		"[>]sig_class" => ["signature.sig_class_id" => "sig_class_id"]
	],[
	'event.cid',
	'event.timestamp',
	'signature.sig_id',
	'sig_class.sig_class_id',
	'sig_class.sig_class_name'
	],[
	'ORDER'=>['event.cid'=>'DESC']
	]);
	$sig_type = array_unique(array_column($data, 'sig_class_name'));

	echo json_encode([
		'status' => 0,
		'data' => $data,
		'sig_type' => $sig_type
	]);
}catch(Exception $err){
	echo json_encode([
	'status' => -1,
	'msg' => $err
	]);
}


?>