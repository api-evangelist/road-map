<?php
$route = '/road_map/';
$app->post($route, function () use ($app){

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM road_map WHERE title = '" . $title . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$Thisroad_map = mysql_fetch_assoc($Database);
		$road_map_id = $Thisroad_map['ID'];
		}
	else
		{
		$Query = "INSERT INTO road_map(title,image,header,footer)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($image) . "',";
		$Query .= "'" . mysql_real_escape_string($header) . "',";
		$Query .= "'" . mysql_real_escape_string($footer) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$road_map_id = mysql_insert_id();
		}

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
	echo format_json(json_encode($ReturnObject));

	});
?>
