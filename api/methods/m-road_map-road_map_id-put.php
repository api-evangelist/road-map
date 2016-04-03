<?php
$route = '/road_map/:road_map_id/';
$app->put($route, function ($road_map_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$road_map_id = prepareIdIn($road_map_id,$host);
	$road_map_id = mysql_real_escape_string($road_map_id);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM road_map WHERE ID = " . $road_map_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE road_map SET ";
		$query .= "title = '" . mysql_real_escape_string($title) . "'";
		$query .= ", image = '" . mysql_real_escape_string($image) . "'";
		$query .= ", header = '" . mysql_real_escape_string($header) . "'";
		$query .= ", footer = '" . mysql_real_escape_string($footer) . "'";
		$query .= " WHERE road_map_id = " . $road_map_id;
		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$title = $Database['title'];
	$image = $Database['image'];
	$header = $Database['header'];
	$footer = $Database['footer'];

	$KeysQuery = "SELECT * from road_map rm";
	$KeysQuery .= " WHERE road_map_id = " . $road_map_id;
	$KeysQuery .= " ORDER BY title ASC";
	$KeysResults = mysql_query($KeysQuery) or die('Query failed: ' . mysql_error());

	$road_map_id = prepareIdOut($road_map_id,$host);

	$F = array();
	$F['road_map_id'] = $road_map_id;
	$F['title'] = $title;
	$F['image'] = $image;
	$F['header'] = $header;
	$F['footer'] = $footer;

	// Keys
	$F['road_map_history'] = array();
	while ($Road_Map_History = mysql_fetch_assoc($Road_Map_History_Results))
		{
		$title = $issues_history['title'];
		$description = $issues_history['description'];
		$url = $issues_history['url'];
		$data = $issues_history['data'];

		$H = array();
		$H['title'] = $title;
		$H['description'] = $description;
		$H['url'] = $url;
		$H['data'] = $data;
		array_push($F['road_map_history'], $H);
		}

	$ReturnObject = $F;
	}

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
