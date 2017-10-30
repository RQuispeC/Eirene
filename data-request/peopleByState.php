<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT estado, SUM(numbeOfPersons) total
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
GROUP BY estado;";
$conn->set_charset("utf8");
$result = $conn->query($sql);
$myArray = array();
if ($result->num_rows > 0) {
        $tot = 0;
        while ($row = mysqli_fetch_assoc($result) ) {
                if ( $row['estado'] == "#N/A" or $row['estado'] == 'NULL' )
                { continue; }
                $tot = $tot + $row['total'];
        }
        $result = $conn->query($sql);
        while ($row = mysqli_fetch_assoc($result) ) {
                if ( $row['estado'] == "#N/A" or $row['estado'] == 'NULL' )
                { continue; }
                $row['total'] = ($row['total'] / $tot) * 100;
                $myArray[] = $row;
        }
        echo json_encode($myArray);
	$fp = fopen('json/peopleByState.json', 'w');
        fwrite($fp, json_encode($myArray));
        fclose($fp);
}

$conn->close();

?>

