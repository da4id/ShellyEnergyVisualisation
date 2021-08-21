<?php

include_once 'db.php';

function getData($pdo,$dbIdDevice)
{
    $sql = "SELECT dbid, startTimestamp FROM `Series` where dbIdDevice = ".$dbIdDevice." ORDER BY startTimestamp DESC";
    $result = $pdo->prepare($sql);
    $result->execute();

    $data = $result->fetchAll();
    $out = array();
	$first = true;
	
    foreach ($data as &$row) {
		if($first == true){
			array_push($out,(object)array('name' => $row['startTimestamp'],'value' => $row['dbid'],'selected' => true));
			$first = false;
		}else{
        	array_push($out,(object)array('name' => $row['startTimestamp'],'value' => $row['dbid']));
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
