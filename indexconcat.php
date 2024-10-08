<?php
require "connect.php";
require "lapozo.php";

$kifejezes = (isset($_POST['kifejezes'])) ? $_POST['kifejezes'] : "";
$sql = "SELECT *
		FROM nevjegyek
		WHERE (
			nev LIKE '%{$kifejezes}%'
			OR cegnev LIKE '%{$kifejezes}%'
			OR mobil LIKE '%{$kifejezes}%'
			OR email LIKE '%{$kifejezes}%'
		)
		ORDER BY nev ASC
		LIMIT {$honnan}, {$mennyit}";
$eredmeny = mysqli_query($dbconn, $sql);

if (@mysqli_num_rows($eredmeny) < 1) {
	$kimenet = "<article>
		<h2>Nincs találat a rendszerben!</h2>
	</article>\n";
}
else {
	$kimenet = "";
	while ($sor = mysqli_fetch_assoc($eredmeny)) {
		$kimenet.= "<article>
		
			<img src=\"kepek/{$sor['foto']}\" alt=\"{$sor['nev']}\">

			<h2>{$sor['nev']}</h2>
			<h3>{$sor['cegnev']}</h3>
			<p>Mobil: <a href=\"tel:{$sor['mobil']}\">{$sor['mobil']}</a></p>
			<p>E-mail: <a href=\"mailto:{$sor['email']}\">{$sor['email']}</a></p>
		</article>\n";
	}
}
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Névjegykártyák</title>
<link href="stilus.css" rel="stylesheet">
</head>

<body>
<h1>Névjegykártyák</h1>
<form method="post" action="">
	<input type="search" id="kifejezes" name="kifejezes">
</form>
<?php print $lapozo; ?> <!-- Rekordok száma kiválasztása (lapozóhoz) -->
    <form method="get" action="">
        <label for="mennyit">Rekordok száma oldalanként:</label>
        <select id="mennyit" name="mennyit" onchange="this.form.submit()">
            <option value="9" <?php if ($mennyit == 9) echo 'selected'; ?>>9</option>
            <option value="30" <?php if ($mennyit == 30) echo 'selected'; ?>>30</option>
            <option value="60" <?php if ($mennyit == 60) echo 'selected'; ?>>60</option>
        </select>
        <input type="hidden" name="oldal" value="<?php echo $aktualis; ?>">
    </form>
<div class="container">
<?php print $kimenet; ?>
</div>
</body>
</html>
