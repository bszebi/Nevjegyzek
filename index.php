<?php
require "connect.php";

$sql = "SELECT * 
        FROM nevjegyek";
$result = mysqli_query($dbconn, $sql);
//var_dump($result);

//lapozó
$osszes = mysqli_num_rows($result);
var_dump($osszes);

$mennyit = 9;
$lapok = ceil($osszes / $mennyit);
var_dump($lapok);

$aktualis = isset($_GET['oldal']) ? (int)$_GET['oldal']: 1;
$honnan = ($aktualis-1)*$mennyit;



//lapozó
$lapozo = '<p> ';
$lapozo .= $aktualis != 1 ? ' <a href="?oldal=1">Első</a> ': 'Első |';
$lapozo .= ($aktualis > 1 && $aktualis <= $lapok) ? '<a href="?oldal='.($aktualis-1).'">Előző</a>' :'Előző |';
for ($oldal=1; $oldal <= $lapok; $oldal++) {
    $lapozo .= ($aktualis != $oldal ) ? "<a href=\"?oldal={$oldal}\">{$oldal}</a> |": $oldal.' | ';
};
$lapozo .= ($aktualis > 0 && $aktualis < $lapok) ? '<a href="?oldal='.($aktualis+1).'">Következő</a>' :'Következő |';
$lapozo .= $aktualis != $lapok ? " <a href=\"?oldal= {$lapok}\">Utolsó</a> ": 'Utolsó |';
$lapozo .= ' </p> ';

$kifejezes = isset($_POST['kifejezes']) ? $_POST['kifejezes'] : "";
//var_dump($kifejezes);
$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE '%{$kifejezes}%'
        OR cegnev LIKE '%{$kifejezes}%'
        OR email LIKE '%{$kifejezes}%'
        )
        ORDER BY nev ASC
        LIMIT {$honnan}, {$mennyit}";

$result = mysqli_query($dbconn, $sql);

if(@mysqli_num_rows($result)<1){
    $kimenet = "<article>
    <h2>Nincs ilyen találat a rendszerben!</h2>
    </article>\n";
}else{
    $kimenet ="";
    while ($sor = mysqli_fetch_assoc($result)) {
        $kimenet .= "<article>
        <img src=\"kepek/{$sor['foto']}\" alt=\"{$sor['nev']}\"
    <h2>{$sor['nev']}</h2>
    <h3>{$sor['cegnev']}</h3>
    </article>\n";
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilus.css">
    <title>Document</title>
</head>
<body>
    <h1>Névjegyzék</h1>
    <form method="post">
        <input type="search" name="kifejezes" id="kifejezes">
    </form>
    <!--lapozó-->
    <div class="container">
        <!--kimenet ->adatbázisból beolvasott adatok-->
        <?php print $lapozo; ?>
        <?php print $kimenet; ?>
    </div>
</body>
</html>