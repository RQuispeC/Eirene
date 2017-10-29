<?php
header('Content-Type: text/html; charset = utf-8');

function queryStr($state){
    $sql = "SELECT nome_curso as tipo, count(nome_curso) AS cantidade  FROM(
    SELECT nome_curso,estado FROM htc_alunos_graduacao_naturalidade WHERE pais='BR'
    UNION ALL
    SELECT nome_curso,estado
    FROM 
      (SELECT nome_curso, cidade
          FROM
          (  
          SELECT nome_curso, cidade
          FROM htc_alunos_mestrado_naturalidade WHERE pais='BR'
          UNION ALL
          SELECT nome_curso, cidade
          FROM htc_alunos_doutorado_naturalidade WHERE pais='BR' 
          ) t 
      ) t1
    LEFT JOIN
      (SELECT cidade, estado FROM htc_alunos_graduacao_naturalidade WHERE pais='BR' GROUP BY cidade, estado ) t2
    ON (t1.cidade = t2.cidade) ) t3 WHERE estado='".$state."' GROUP BY nome_curso ;";
#       print $sql;
    return $sql;
}

function formatString($str){
    $str = strtolower(str_replace(' ', '', $str));
    $re = "/[^a-zA-Z0-9 ]/";
    $str = preg_replace($re, '', $str);
    return $str;
}

$servername = "143.106.73.88";
$username = "htc";
$password = "htc_123456";
$dbname = "hackthecampus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$states_list = array('Bahia', 'São Paulo', 'Acre', 'Alagoas', 'Amapá', 'Amazonas', 'Ceará', 'Distrito Federal', 'Espírito Santo', 'Goiás', 'Maranhão', 'Mato Grosso', 'Mato Grosso do Sul', 'Minas Gerais', 'Pará', 'Paraíba', 'Paraná', 'Pernambuco', 'Piauí', 'Rio de Janeiro', 'Rio Grande do Norte', 'Rio Grande do Sul', 'Rondônia', 'Roraima', 'Santa Catarina', 'Sergipe', 'Tocantins');
foreach($states_list as $state){
        print $state;
    $sql = queryStr($state);
    $conn->set_charset("utf8");
    $result = $conn->query($sql);
    $myArray = array();
    if ($result->num_rows > 0) {
            $tot = 0;
            while ($row = mysqli_fetch_assoc($result) ) {
                    echo '<pre>'; echo $tot; echo '</pre>';
                    echo '<pre>'; echo $row['cantidade']; echo '</pre>';
                    $tot = $tot + $row['cantidade'];
            }
            $result = $conn->query($sql);
            while ($row = mysqli_fetch_assoc($result) ) {
                    echo '<pre>';  echo $row['cantidade']; echo '</pre>';
                    $row['cantidade'] = ($row['cantidade'] / $tot) * 100;
                    echo '<pre>'; echo $row['cantidade']; echo '</pre>';
                    $myArray[] = $row;
            }
            echo json_encode($myArray);
            $fp = fopen('json/peopleByCourse_'.formatString($state).'.json', 'w');
            fwrite($fp, json_encode($myArray));
            fclose($fp);
    }
    else print "no items";
}
$conn->close();

?>

