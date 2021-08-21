<?php

include_once 'db.php';

function getData($pdo,$dbIdSeries)
{
    $sql = "SELECT dbid,channelId, energy FROM `Channel` where dbIdSeries = ".$dbIdSeries." ORDER BY channelId";
    $result = $pdo->prepare($sql);
    $result->execute();

    $data = $result->fetchAll();
    $out = array();
	$first = true;
	
    foreach ($data as &$row) {
		if($first == true){
			array_push($out,(object)array('name' => $row['channelId'],'value' => $row['dbid'],'selected' => true,'energy' => floatval($row['energy'])));
			$first = false;
		}else{
        	array_push($out,(object)array('name' => $row['channelId'],'value' => $row['dbid'],'energy' => floatval($row['energy'])));
		}
    }
	
    return $out;
}

header("Content-type:application/json");

$pdo = createPDO();
if($_GET){
	if(!empty($_GET)){
		if(!empty($_GET['id'])){
			$dbid = intval($_GET['id']);
			$data = getData($pdo,$dbid);
			echo json_encode($data);
		}
	}
}


?>
