<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php


  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  VerifyOfficesTable($connection, DB_DATABASE);

  $numFuncionarios = htmlentities($_POST['NUM_FUNCIONARIOS']);
  $rendaMensal = htmlentities($_POST['RENDA_MENSAL']);
  $gerente = htmlentities($_POST['GERENTE']);
  $localizacao = htmlentities($_POST['LOCALIZACAO']);

  if (strlen($numFuncionarios) || strlen($rendaMensal) || strlen($gerente) || strlen($localizacao)) {
    AddOffice($connection, $numFuncionarios, $rendaMensal, $gerente, $localizacao);
  }
?>

<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>Número de Funcionários</td>
      <td>Renda Mensal</td>
      <td>Gerente</td>
      <td>Localização</td>
    </tr>
    <tr>
      <td>
        <input type="number" name="NUM_FUNCIONARIOS" maxlength="10" size="20" />
      </td>
      <td>
        <input type="text" name="RENDA_MENSAL" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="GERENTE" maxlength="100" size="50" />
      </td>
      <td>
        <input type="text" name="LOCALIZACAO" maxlength="100" size="50" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>Número de Funcionários</td>
    <td>Renda Mensal</td>
    <td>Gerente</td>
    <td>Localização</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM escritorios");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>";
  echo "</tr>";
}
?>

</table>

<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>

<?php

function AddOffice($connection, $numFuncionarios, $rendaMensal, $gerente, $localizacao) {
   $numFuncionarios = mysqli_real_escape_string($connection, $numFuncionarios);
   $rendaMensal = mysqli_real_escape_string($connection, $rendaMensal);
   $gerente = mysqli_real_escape_string($connection, $gerente);
   $localizacao = mysqli_real_escape_string($connection, $localizacao);

   $query = "INSERT INTO escritorios (num_funcionarios, renda_mensal, gerente, localizacao) 
             VALUES ('$numFuncionarios', '$rendaMensal', '$gerente', '$localizacao');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding office data.</p>");
}

function VerifyOfficesTable($connection, $dbName) {
  if(!TableExists("escritorios", $connection, $dbName))
  {
     $query = "CREATE TABLE escritorios (
         id_escritorio int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         num_funcionarios INT,
         renda_mensal FLOAT,
         gerente TEXT,
         localizacao TEXT
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
