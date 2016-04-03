<?php
$route = '/road_map/:road_map_id/';
$app->delete($route, function ($road_map_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$road_map_id = prepareIdIn($road_map_id,$host);
	$road_map_id = mysql_real_escape_string($road_map_id);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM road_map WHERE road_map_id = " . $road_map_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
