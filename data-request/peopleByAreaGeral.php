<?php
header('Content-Type: text/html; charset = utf-8');

function queryStr($state){
    $sql="select * from (SELECT 'ciencias_humanas' as tipo,@var1 as cantidade UNION ALL
    SELECT 'ciencias_humanas' as tipo,@var2 as cantidade UNION ALL
    SELECT 'engenheria' as tipo,@var3 as cantidade UNION ALL
    SELECT 'ciencias_agrarias' as tipo,@var4 as cantidade UNION ALL
    SELECT 'ciencias_biologicas' as tipo,@var5 as cantidade UNION ALL
    SELECT 'linguistica_artes' as tipo,@var6 as cantidade UNION ALL
    SELECT 'ciencias_da_saude' as tipo,@var7 as cantidade UNION ALL
    SELECT 'ciencias_sociais' as tipo,@var8 as cantidade UNION ALL
    SELECT 'interdisciplinarias' as tipo, interdisciplinarias  as cantidade FROM (
SELECT MAX(IF(nome_curso='Administração' or 
nome_curso='Administração Pública' or 
nome_curso='Gestão de Políticas Públicas' or 
nome_curso='Gestão de Empresas' or 
nome_curso='Gestão de Comércio Internacional' or 
nome_curso='Ciências do Esporte' or 
nome_curso='Filosofia' or 
nome_curso='Educação Física' or 
nome_curso='Ciências Econômicas',@var1:=@var1+1,@var1)) as ciencias_humanas, MAX(IF(nome_curso='Matemática' or 
nome_curso='Estatística' or 
nome_curso='Física' or nome_curso='Química' or 
nome_curso='Matemática Aplicada e Computacional' or 
nome_curso='Licenciatura em Matemática' or 
nome_curso='Engenharia de Computação' or 
nome_curso='Licenciatura em Física' or 
nome_curso='Matemática/Física/Matemática Aplicada e Computacional' or 
nome_curso='Química Tecnológica' or 
nome_curso='Ciências da Terra' or 
nome_curso='Geologia' or 
nome_curso='Geografia' or 
nome_curso='Licenciatura Integrada Química/Física' or 
nome_curso='Curso Superior de Tecnologia em Sistemas de Telecomunicações' or 
nome_curso='Curso Superior de Tecnologia em Construção de Edifícios' or 
nome_curso='Curso Superior de Tecnologia em Estradas' or 
nome_curso='Sistemas de Informação',@var2:=@var2+1,@var2)) as 
ciencias_exatas, MAX(IF(nome_curso='Engenharia Ambiental' or 
nome_curso='Engenharia de Telecomunicações' or 
nome_curso='Engenharia Física' or 
nome_curso='Engenharia de Produção' or 
nome_curso='Engenharia de Manufatura' or 
nome_curso='Arquitetura e Urbanismo' or 
nome_curso='Engenharia de Controle e Automação' or 
nome_curso='Curso Superior de Tecnologia da Construção Civil' or 
nome_curso='Curso Superior de Tecnologia em Análise e Desenvolvimento de Sistemas' or
nome_curso='Curso Superior de Tecnologia da Construção Civil - Obras de Solos' or 
nome_curso='Curso Superior de Tecnologia da Construção Civil - Edifícios' or 
nome_curso='Curso Superior de Tecnologia Sanitária' or 
nome_curso='Engenharia de Alimentos' or 
nome_curso='Engenharia Civil' or nome_curso='Engenharia Elétrica' or 
nome_curso='Engenharia Mecânica' or 
nome_curso='Engenharia Química', @var3:=@var3+1,@var3)) as engenheria, 
MAX(IF(nome_curso='Superior Tecnologia em Saneamento Ambiental' or 
nome_curso='Superior de Tecnologia Ambiental' or nome_curso='Gestão do Agronegócio' or 
nome_curso='Curso Superior de Tecnologia em Saneamento Ambiental' or 
nome_curso='Engenharia Agrícola' ,@var4:=@var4+1,@var4)) as ciencias_agrarias, 
MAX(IF(nome_curso='Ciências Biológicas' or 
nome_curso='Licenciatura em Ciências Biológicas',@var5:=@var5+1,@var5)) as ciencias_biologicas, 
MAX(IF(nome_curso='Licenciatura em Letras - Português' or 
nome_curso='Linguística' or 
nome_curso='Pedagogia' or 
nome_curso='Música' or 
nome_curso='Dança' or 
nome_curso='Artes Visuais' or 
nome_curso='Artes Cênicas' or 
nome_curso='Curso de Música Composição e Regência' or 
nome_curso='Curso de Música Popular' or 
nome_curso='Comunicação Social - Midialogia' or 
nome_curso='Estudos Literários',@var6:=@var6+1,@var6)) as linguistica_artes, 
MAX(IF(nome_curso='Odontologia' or 
nome_curso='Medicina' or 
nome_curso='Enfermagem' or 
nome_curso='Fonoaudiologia' or 
nome_curso='Farmácia' or 
nome_curso='Nutrição',@var7:=@var7+1,@var7)) as ciencias_da_saude, 
MAX(IF(nome_curso='Ciências Sociais' or 
nome_curso='História',@var8:=@var8+1,@var8)) as ciencias_sociais, 
MAX(IF(nome_curso='Programa de Formação Interdisciplinar Superior - ProFIS', @var9:=@var9+1,@var9)) as interdisciplinarias 
FROM( SELECT nome_curso,estado FROM htc_alunos_graduacao_naturalidade WHERE pais='BR'
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
ON (t1.cidade = t2.cidade)) t3 WHERE estado='São Paulo')t5 )t4;";
#       print $sql;    
    return $sql;
}

function formatString($str){
    $str = strtolower(str_replace(' ', '', $str));
    $re = "/[^a-zA-Z0-9 ]/";
    $str = preg_replace($re, '', $str);
    return $str;
}

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$states_list = array('Bahia', 'São Paulo', 'Acre', 'Alagoas', 'Amapá', 'Amazonas', 'Ceará', 'Distrito Federal', 'Espírito Santo', 'Goiás', 'Maranhão', 'Mato Grosso', 'Mato Grosso do Sul', 'Minas Gerais', 'Pará', 'Paraíba', 'Paraná', 'Pernambuco', 'Piauí', 'Rio de Janeiro', 'Rio Grande do Norte', 'Rio Grande do Sul', 'Rondônia', 'Roraima', 'Santa Catarina', 'Sergipe', 'Tocantins');
foreach($states_list as $state){
        print $state;
    $sql = "set @var1:=0, @var2:=0, @var3:=0, @var4:=0, @var5:=0, @var6:=0, @var7:=0,@var8:=0,@var9:=0;" ;
    $conn->query($sql);
    $sql2 = queryStr($state);
    $conn->set_charset("utf8");
    $result = $conn->query($sql2);
    $myArray = array();
    if ($result->num_rows > 0) {
            $tot = 0;
            while ($row = mysqli_fetch_assoc($result) ) {
                    echo '<pre>'; echo $tot; echo '</pre>';
                    echo '<pre>'; echo $row['cantidade']; echo '</pre>';
                    $tot = $tot + $row['cantidade'];
            }
            $conn->query($sql);
            $result = $conn->query($sql2);
            while ($row = mysqli_fetch_assoc($result) ) {
                    echo '<pre>';  echo $row['cantidade']; echo '</pre>';
                    $row['cantidade'] = ($row['cantidade'] / $tot) * 100;
                    echo '<pre>'; echo $row['cantidade']; echo '</pre>';
                    $myArray[] = $row;
            }
            echo json_encode($myArray);
            $fp = fopen('json/peopleByArea_'.formatString($state).'.json', 'w');
            fwrite($fp, json_encode($myArray));
            fclose($fp);
    }
    else print "no items";
}
$conn->close();

?>

