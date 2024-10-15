<?php 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name=""MAX_FILE_SIZE value="2000000"><br><br>
        <p><label for="foto">Fotó:</label></p>
        <input type="file" name="foto" id="foto">

        <p><label for="foto">Nev:</label></p>
        <input type="text" name="nev" id="nev">

        <p><label for="foto">Cégnév:</label></p>
        <input type="text" name="cegnev" id="cegnev">

        <p><label for="foto">Telefon:</label></p>
        <input type="tel" name="telefon" id="telefon">

        <p><label for="foto">Email:</label></p>
        <input type="email" name="email" id="email">

        <input type="submit" value="Rendben" name="Rendben" id="Rendben">
        <input type="reset" value="Mégsem" name="Mégsem" id="Mégsem">


    </form>
</body>
</html>