<?php
$servername = "143.106.73.88";
$username = "htc";
$password = "htc_123456";
$dbname = "hackthecampus";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT pais, SUM(numbeOfPersons) total
FROM
(
SELECT pais, COUNT(*) numbeOfPersons
FROM htc_alunos_graduacao_naturalidade GROUP BY pais
UNION ALL
SELECT pais, SUM(numbeOfPersons) numbeOfPersons
FROM
(  
    SELECT pais, COUNT(*) AS numbeOfPersons 
    FROM htc_alunos_mestrado_naturalidade GROUP BY pais 
    UNION ALL
    SELECT pais, COUNT(*) AS numbeOfPersons 
    FROM htc_alunos_doutorado_naturalidade GROUP BY pais
) t
GROUP BY pais
) q
GROUP BY pais;";

$result = $conn->query($sql);
$myArray = array();
if ($result->num_rows > 0) {
        $tot = 0;
        while ($row = mysqli_fetch_assoc($result) ) {
		if ( $row['pais'] == "#N/A" or $row['pais'] == 'NULL' or $row['pais'] == 'BR')
		{ continue; }
		echo '<pre>'; echo $tot; echo '</pre>';
		echo '<pre>'; echo $row['total']; echo '</pre>';
                $tot = $tot + $row['total'];
	}
	$result = $conn->query($sql);	
        while ($row = mysqli_fetch_assoc($result) ) {
		if ( $row['pais'] == "#N/A" or $row['pais'] == 'NULL' or $row['pais'] == 'BR')
                { continue; }
                echo '<pre>';  echo $row['total']; echo '</pre>';
                $row['total'] = ($row['total'] / $tot) * 100;
                echo '<pre>'; echo $row['total']; echo '</pre>';
                $myArray[] = $row;
        }
        echo json_encode($myArray);
        $fp = fopen('json/peopleByCountry.json', 'w');
        fwrite($fp, json_encode($myArray));
        fclose($fp);
}

$conn->close();

?>
