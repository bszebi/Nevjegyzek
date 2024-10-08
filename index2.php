<?php
require "connect.php";

$sql = "SELECT * 
        FROM nevjegyek";
$result = mysqli_query($dbconn, $sql);
//var_dump($result);

require "lapozo.php";


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

<?php require "head.html";?>
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