<?php
/** 
@author heting
*/

require_once 'medoo.php';

use Medoo\Medoo;
// 初始化配置
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'snort',
    'server' => '10.170.66.164',
    'username' => 'snort',
    'password' => '123456',
    'charset' => 'utf8'
]);

