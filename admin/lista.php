<?php
require "../connect.php";

$kifejezes = $_POST['kifejezes'] ?? "";
$rendez = $_GET['rendez'] ?? "nev";
$mennyit = $_GET['mennyit'] ?? 9; // Default value if not set

$validColumns = ['nev', 'cegnev', 'mobil', 'email'];
$rendez = in_array($rendez, $validColumns) ? $rendez : "nev";    

$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE ? 
        OR mobil LIKE ? 
        OR cegnev LIKE ? 
        OR email LIKE ?
        )
        ORDER BY {$rendez} ASC 
        LIMIT ?"; // Adjust the LIMIT

$stmt = $dbconn->prepare($sql);

if ($stmt) {
    $searchTerm = "%{$kifejezes}%";
    $stmt->bind_param("ssssi", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $mennyit); // 'i' for integer
    $stmt->execute();
    $eredmeny = $stmt->get_result();

    if ($eredmeny) {
        $kimenet = "
        <table>
        <tr>
            <th>Fotó</th>
            <th><a href=\"?rendez=nev\">Név</a></th>
            <th><a href=\"?rendez=cegnev\">Cégnév</a></th>
            <th><a href=\"?rendez=mobil\">Mobil</a></th>
            <th><a href=\"?rendez=email\">E-mail</a></th>
            <th>Művelet</th>
        </tr>";

        while ($sor = $eredmeny->fetch_assoc()) {
            $foto = htmlspecialchars($sor['foto'], ENT_QUOTES, 'UTF-8');
            $cegnev = htmlspecialchars($sor['cegnev'], ENT_QUOTES, 'UTF-8');
            $mobil = htmlspecialchars($sor['mobil'], ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($sor['email'], ENT_QUOTES, 'UTF-8');
            $nev = htmlspecialchars($sor['nev'], ENT_QUOTES, 'UTF-8');
            $kimenet .= "
            <tr>
                <td><img src=\"../kepek/{$foto}\" alt=\"{$nev}\"></td>
                <td>{$nev}</td>
                <td>{$cegnev}</td>
                <td>{$mobil}</td>
                <td>{$email}</td>
                <td><a href=\"torles.php?id={$sor['id']}\">Törlés</a> <a href=\"modositas.php?id={$sor['id']}\">Módosítás</a></td>
            </tr>";
        }
        $kimenet .= "</table>";
    }

    $stmt->close();
} else {
    echo "Hiba a keresés során!";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Névjegyzék</title>
</head>
<body>
    <h1>Névjegyzék</h1>

    <!-- Keresési mező -->
    <form method="post">
        <input type="search" name="kifejezes" id="kifejezes" value="<?php echo htmlspecialchars($kifejezes); ?>">
    </form>

    <!-- Névjegykártyák száma kiválasztás -->
    <form method="get">
        <label for="mennyit">Névjegykártyák száma oldalanként:</label>
        <select name="mennyit" id="mennyit" onchange="this.form.submit()">
            <option value="9" <?php if ($mennyit == 9) echo 'selected'; ?>>9</option>
            <option value="30" <?php if ($mennyit == 30) echo 'selected'; ?>>30</option>
            <option value="60" <?php if ($mennyit == 60) echo 'selected'; ?>>60</option>
        </select>
    </form>

    <p><a href="felvitel.php">Új névjegy felvitele</a></p>

    <!-- Lapozó és névjegykártyák megjelenítése -->
    <?php 
        print $kimenet;
    ?>
</body>
</html>