<?php
$route = '/road_map/:road_map_id/';
$app->get($route, function ($road_map_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$road_map_id = prepareIdIn($road_map_id,$host);
	$road_map_id = mysql_real_escape_string($road_map_id);

	$ReturnObject = array();
	$Query = "SELECT * FROM road_map WHERE road_map_id = " . $road_map_id;
	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$road_map_id = $Database['road_map_id'];
		$title = $Database['title'];
		$image = $Database['image'];
		$header = $Database['header'];
		$footer = $Database['footer'];

		$KeysQuery = "SELECT * from keys k";
		$KeysQuery .= " WHERE road_map_id = " . $road_map_id;
		$KeysQuery .= " ORDER BY name ASC";
		$KeysResults = mysql_query($KeysQuery) or die('Query failed: ' . mysql_error());

		$road_map_id = prepareIdOut($road_map_id,$host);

		$F = array();
		$F['road_map_id'] = $road_map_id;
		$F['title'] = $title;
		$F['image'] = $image;
		$F['header'] = $header;
		$F['footer'] = $footer;

		// Keys
		$F['keys'] = array();
		while ($Keys = mysql_fetch_assoc($KeysResults))
			{
			$name = $Keys['name'];
			$description = $Keys['description'];
			$K = array();
			$K['name'] = $name;
			$K['description'] = $description;
			array_push($F['keys'], $K);
			}

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
