<!DOCTYPE html>
<html lang="cz">
<head>
    <title>Divergence</title>
    <meta charset='utf-8'>
    <meta name='description' content=''>
    <meta name='keywords' content=''>
    <meta name='author' content=''>
    <meta name='robots' content='all'>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
<?php
///////////////////////////////////////////////////////////////////////////////////////
$spojeni = mysqli_connect("localhost","root","","dat_3b")
or die("Problém s připojením k serveru nebo k databázi");

mysqli_set_charset($spojeni,"utf8");

include_once "uvod.php";
$prikaz = "CREATE TABLE IF NOT EXISTS postavy (id INT PRIMARY KEY
 AUTO_INCREMENT, jmeno VARCHAR(50) NOT NULL, vyska INT);";
$vysl = mysqli_query($spojeni,$prikaz);
if ($vysl) echo ('JUHU');
else echo ('Při připojení k databázi došlo k chybě');

/////////////////////////////////////////////////////////////////////////////////////////

$vaha="";
$vyska="";
$jmeno="";
echo"<fieldset>";
echo "<legend>BMI kalkulačka</legend>";
echo "<form action='uvod.php' method='post'>
    Jméno: <input type='text' name='jmeno' value=".$jmeno."><br>
    Váha (kg): <input type='text' name='vaha' value=".$vaha."><br>
    Výška (m): <input type='text' name='vyska' value=".$vyska."><br>
    <input type='submit' name='odeslat' value='odeslat'><input type='submit' name='vypsat' value='vypsat obsah databaze'>
</form>";
echo"</fieldset>";

echo "<br><br><br>";
//////////////////////////////////////////////////////////////////////////
if (isset($_POST['odeslat'])) {
    $vaha=$_POST['vaha'];
    $vyska=$_POST['vyska'];
    $jmeno=$_POST['jmeno'];
    if (is_numeric($vaha) AND is_numeric($vyska) AND !empty($jmeno)){
        $BMI=round(($vaha/($vyska*$vyska)),1,PHP_ROUND_HALF_UP);
        if ($BMI<18.5)  echo "Tvoje BMI je: ".$BMI.".Máš podváhu<br>";
            elseif ($BMI>=18.5 AND $BMI<25)  echo "Tvoje BMI je: ".$BMI."<br>Máš normální váhu<br>";
                elseif ($BMI>=25 AND $BMI<30)  echo "Tvoje BMI je: ".$BMI."<br>Máš nadváhu<br>";
                    elseif ($BMI>=30 AND $BMI<35)  echo "Tvoje BMI je: ".$BMI."<br>Máš obezitu 1.stupeň<br>";
                        elseif ($BMI>=35 AND $BMI<40)  echo "Tvoje BMI je: ".$BMI."<br>Máš obezitu 2.stupeň<br>";
                            elseif ($BMI>=40)  echo "Tvoje BMI je: ".$BMI."<br>Máš obezitu 3.stupeň<br>";
                                else{
                                    echo "Zadej správné hodnoty.<br>";
                                    exit;
                                }

        $vloz = "INSERT INTO postavy (jmeno,vyska,vaha,BMI) VALUES ('$jmeno',$vyska,$vaha,$BMI);";
        $vysledek = mysqli_query($spojeni,$vloz);
    }
    else echo "Zadej správné hodnoty.<br>";
    
}
///////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['vypsat'])){
    $vypis = mysqli_query($spojeni,"SELECT * FROM postavy;");
    $pocet = mysqli_num_rows($vypis);
    if ($pocet > 0) {
        echo "<table width='300px' border='1px solid blue' cellspacing='0px'>";
        echo "<tr>";
        echo "<th>Číslo</th>";
        echo "<th>Jméno</th>";
        echo "<th>Výška v m</th>";
        echo "<th>Váha v kg</th>";
        echo "<th>BMI</th>";
        echo "</tr>";
 while ($zaznam = mysqli_fetch_assoc($vypis)) {
     echo "<tr><td>" . $zaznam['id'] . "</td><td>" . $zaznam['jmeno'] . "</td><td>" . $zaznam['vyska'] . "</td><td>" . $zaznam['vaha'] . "</td><td>" . $zaznam['BMI'] . "</td></tr>";
 }
 echo "</table>";
 }
}

mysqli_close($spojeni);
?>
</body>
</html>
