<?php 
/** 
@author heting
*/

require_once 'config.php';
date_default_timezone_set("PRC");

try{
	$num = 30;
	$numh = 0;
	$dayCountArray = [];
	$stime = time();
	for($i=$num;$i>=0;$i--){  
        $daystr=date('Y-m-d', $stime);
        $searchstr="DATE_FORMAT(`timestamp`, '%Y-%m-%d') = '{$daystr}'";  
        $dayCount=$database->query("SELECT `cid`, `timestamp` as `date` FROM `event` WHERE {$searchstr}")->fetchAll(PDO::FETCH_ASSOC);
        $dayCountArray[$daystr]=count($dayCount);  
        $stime=$stime - 3600*24;
        if($numh<24) {
        	$hourstr = date('Y-m-d', time()).' '.($numh<10 ? '0'.$numh : $numh);
        	$searchstrh="DATE_FORMAT(`timestamp`, '%Y-%m-%d %H') = '{$hourstr}'";  
        	$hourCount=$database->query("SELECT `cid`, `timestamp` as `date` FROM `event` WHERE {$searchstrh}")->fetchAll(PDO::FETCH_ASSOC);
        	$hourCountArray[$numh]=count($hourCount); 
        	$numh++;
        }
    }  
	

	echo json_encode([
		'status' => 0,
		'data' => [
			'month' => $dayCountArray,
			'day' => $hourCountArray
			],
	]);
}catch(Exception $err){
	echo json_encode([
	'status' => -1,
	'msg' => $err
	]);
}


?>