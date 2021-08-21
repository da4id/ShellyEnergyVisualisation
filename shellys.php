<?php

include_once 'db.php';

function getData($pdo)
{
    $sql = "SELECT dbid, name FROM `Device`";
    $result = $pdo->prepare($sql);
    $result->execute();

    $data = $result->fetchAll();
    $out = array();
	$first = true;
	
    foreach ($data as &$row) {
		if($first == true){
			array_push($out,(object)array('name' => $row['name'],'value' => $row['dbid'],'selected' => true));
			$first = false;
		}else{
        	array_push($out,(object)array('name' => $row['name'],'value' => $row['dbid']));
		}
    }
	
    return $out;
}

header("Content-type:application/json");

$pdo = createPDO();
$data = getData($pdo);

echo json_encode($data);

?>
