<?php
$servername = "143.106.73.88";
$username = "htc";
$password = "htc_123456";
$dbname = "hackthecampus";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT estado, SUM(numbeOfPersons) numberOfPersons
FROM
(  
	SELECT estado, COUNT(*) AS numbeOfPersons 
	FROM htc_alunos_graduacao_naturalidade WHERE pais='BR' GROUP BY estado
	UNION ALL
	SELECT estado, SUM(numbeOfPersons) numbeOfPersons
FROM 
  (SELECT cidade, SUM(numbeOfPersons) numbeOfPersons
  FROM
  (  
  SELECT cidade, COUNT(*) AS numbeOfPersons 
  FROM htc_alunos_mestrado_naturalidade WHERE pais='BR' GROUP BY cidade 
  UNION ALL
  SELECT cidade, COUNT(*) AS numbeOfPersons 
  FROM htc_alunos_doutorado_naturalidade WHERE pais='BR' GROUP BY cidade 
  ) t
  GROUP BY t.cidade ) t1
LEFT JOIN
  (SELECT cidade, estado FROM htc_alunos_graduacao_naturalidade WHERE pais='BR' GROUP BY cidade, estado ) t2
ON (t1.cidade = t2.cidade)
GROUP BY estado
) t
GROUP BY estado;
";

$result = $conn->query($sql);
$myArray = array();
if ($result->num_rows > 0) {
	while ($row = mysqli_fetch_assoc($result) ) {
		$myArray[] = json_encode($row);
	}
	echo json_encode($myArray);	
	$fp = fopen('alunos.json', 'w');
	fwrite($fp, json_encode($myArray));
	fclose($fp);
}

$conn->close();

?>
