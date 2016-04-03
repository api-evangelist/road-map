<?php
$route = '/road_map/';
$app->get($route, function ()  use ($app,$contentType,$githuborg,$githubrepo){

	$ReturnObject = array();
	//$ReturnObject["contentType"] = $contentType;

	if($contentType == 'application/apis+json')
		{
		$app->response()->header("Content-Type", "application/json");

		$apis_json_url = "http://" . $githuborg . ".github.io/" . $githubrepo . "/apis.json";
		$apis_json = file_get_contents($apis_json_url);
		echo stripslashes(format_json($apis_json));
		}
	else
		{

	 	$request = $app->request();
	 	$params = $request->params();

		if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
		if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
		if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 50;}
		if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'title';}
		if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'ASC';}

		// Pull from MySQL
		if($query!='')
			{
			$Query = "SELECT * FROM road_map WHERE title LIKE '%" . $query . "%' OR header LIKE '%" . $query . "%' OR footer LIKE '%" . $query . "%'";
			}
		else
			{
			$Query = "SELECT * FROM road_map";
			}
			$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
			//echo $Query . "<br />";
			$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

			while ($Database = mysql_fetch_assoc($DatabaseResult))
				{

				$road_map_id = $Database['road_map_id'];
				$title = $Database['title'];
				$image = $Database['image'];
				$header = $Database['header'];
				$footer = $Database['footer'];

				$Road_Map_Query = "SELECT * from road_map rm";
				$Road_Map_Query .= " WHERE road_map_id = " . $road_map_id;
				$Road_Map_Query .= " ORDER BY title ASC";
				$Road_Map_Results = mysql_query($Road_Map_Query) or die('Query failed: ' . mysql_error());

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
			}
	});
?>
