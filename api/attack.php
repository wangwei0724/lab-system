<?php 
/** 
@author heting
*/

require_once 'config.php';

try{
	$pgIndex = $_GET['pgIndex'] ? $_GET['pgIndex'] : 1;
	$pgSize = $_GET['pgSize'] ? $_GET['pgSize'] : 25;
	$class = $_GET['class'] ? $_GET['class'] : 'all';

	$where = [
	'ORDER'=>['event.cid'=>'DESC']
	];
	if($class != 'all'){
		$where['sig_class_name'] = $class;
	}

	$count = $database->count("event", [
		"[>]signature" => ["signature" => "sig_id"],
		"[>]sig_class" => ["signature.sig_class_id" => "sig_class_id"]
	],'*',$where);

	$where['LIMIT'] = [($pgIndex-1) * $pgSize + 1, $pgIndex * $pgSize];

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
	'sig_class.class',
	],$where);
	

	foreach ($data as $key => $val) {
		$data[$key]['ip_src'] = long2ip($val['ip_src']);
		$data[$key]['ip_dst'] = long2ip($val['ip_src']);		
	}
	$sig_type = array_unique(array_column($database->select("event", [
		"[>]signature" => ["signature" => "sig_id"],
		"[>]sig_class" => ["signature.sig_class_id" => "sig_class_id"]
	],'sig_class.sig_class_name'), 'sig_class_name'));

	echo json_encode([
		'status' => 0,
		'data' => $data,
		'sig_type' => $sig_type,
		'count' => $count
	]);
}catch(Exception $err){
	echo json_encode([
	'status' => -1,
	'msg' => $err
	]);
}


?>