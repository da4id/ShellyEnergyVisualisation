<?php

include_once 'db.php';

function getData($pdo,$dbIdChannel)
{
    $sql = "SELECT Timestamp, ROUND(AVG(value),1) as Value FROM `Measurement` where dbIdChannel = ".$dbIdChannel." GROUP BY EXTRACT(YEAR_MONTH FROM timestamp), EXTRACT(DAY_HOUR FROM timestamp),FLOOR(EXTRACT(MINUTE FROM timestamp)/5) ORDER BY timestamp";
    $result = $pdo->prepare($sql);
    $result->execute();

    $data = $result->fetchAll();
    $out = array();

    foreach ($data as &$row) {
        array_push($out,(object)array('date' => $row['Timestamp'],'Value' => floatval($row['Value'])));
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
