<?php
require "../connect.php";
//$kifejezes = isset($_POST['kifejezes']) ? $_POST['kifejezes'] : "";

$rendez = $_POST['rendez'] ?? 'nev';
$kifejezes = $_POST['kifejezes'] ?? "";

$validColumns = ['nev', 'cegnev', 'mobil', 'email'];
$rendez = in_array($rendez, $validColumns) ? $rendez : 'nev';

$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE ?
        OR mobil LIKE ?
        OR cegnev LIKE ?
        OR email LIKE ?
        )
        ORDER BY ($rendez) ASC
        
        ";

//$result = mysqli_query($dbconn, $sql);
//$stmt = mysqli_prepare($dbconn, $sql);
$stmt = $dbconn -> prepare($sql);
//Előkészítés ell.
if($stmt){
    $searchTerm = "%{$kifejezes}%";
    //mysqli_stmt_bind_param($stmt,'ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt -> bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt -> execute();
    //$eredmeny = mysqli_stmt_get_result($stmt);
    $eredmeny = $stmt->get_result();
    if($eredmeny){
        $kimenet = "<table>
        <tr>
            <th>Fotó</th>
            <th><a href=\"?rendez=nev\">Név</a></th>
            <th><a href=\"?rendez=cegnev\">Cégnév</a></th>
            <th><a href=\"?rendez=mobil\">Mobil</a></th>
            <th><a href=\"?rendez=email\">E-mail</a></th>
            <th>Művelet</th>
        </tr>";
        while($sor = $eredmeny->fetch_assoc()){
            $foto = htmlspecialchars($sor['foto'], ENT_QUOTES, 'utf-8');            
            $cegnev = htmlspecialchars($sor['cegnev'], ENT_QUOTES, 'utf-8');
            $mobil = htmlspecialchars($sor['mobil'], ENT_QUOTES, 'utf-8');
            $email = htmlspecialchars($sor['email'], ENT_QUOTES, 'utf-8');
            $nev = htmlspecialchars($sor['nev'], ENT_QUOTES, 'utf-8');
            $kimenet .= "<tr>
            <td><img src=\"../kepek/{$foto}\" alt=\"{$nev}\"></td>
            <td>{$nev}</td>
            <td>{$cegnev}</td>
            <td>{$mobil}</td>
            <td>{$email}</td>
            <td><a href=\"torles.php?id{$sor['id']}\">Törlés</a> | <a href=\"modositas.php\">Módosítás</a></td>
        </tr>";
        }
        $kimenet .= "</table>";
    }
    //mysqli_stmt_close($stmt);
    $stmt -> close();
}
else{
    echo "Hiba az előkészített utasítás létrehozásában!" . mysqli_error($dbconn);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Document</title>
</head>
<body>
    <h1>Névjegyzék</h1>
    <form method="post">
        <input type="search" name="kifejezes" id="kifejezes">
    </form>
    <p><a href="felvitel.php">Új névjegy felvitele</a></p>
    <!--lapozó-->
    <div class="container">
        <!--kimenet ->adatbázisból beolvasott adatok-->
        <?php print $kimenet; ?>
    </div>
</body>
</html>