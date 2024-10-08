<?php 

if(isset($_POST['rendben'])){

    //változók mentése és tisztítása
    $nev = htmlspecialchars(strip_tags(ucwords(strtolower(trim($_POST['nev'])))));
   // var_dump($nev);
   $cegnev = htmlspecialchars(strip_tags(ucwords(strtolower(trim($_POST['cegnev'])))));
   $mobil = htmlspecialchars(strip_tags(trim($_POST['mobil'])));
   $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
   //print_r($cegnev);
   //print_r($mobil);
   //print_r($email);

   //képfájlok megengedett típusainak tárolása
   $mime = ["image/jpeg", "image/jpg", "image/gif", "image/png"];

   if(empty($nev)){
    $hibak[] = "Nem adott meg nevet!";
   }elseif(strlen($nev) < 3){
    $hibak[] = "Az Ön neve gyanusan rövid, hacsak Ön nem kínai!";
   }

   if(!empty($mobil)){
    if(strlen($mobil) <9 ){
        $hibak[] = "Ön a segélyhívót akarta hívni?";
    }/*elseif(!preg_match("/^[0-9]{9,}$/", $mobil)){
        $hibak[] = "A mobil szám csak számokat tartalmazhat";
    }*/
   }

   if(!empty($email)){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $hibak[] = "Nem megfelelő az e-mail formátuma";
    }
   }

   if($_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 20000000){
        $hibak[] = "Túl nagy méretű képet tölt fel, max 2MB";
   }

   if($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime))
        $hibak[] = "Nem megfelelő képformátum!";

        //kép nevének összeállítása
        switch($_FILES['foto']['type']){
            case "image/png": $kit = ".png"; break;
            case "image/gif": $kit = ".gif"; break;
            case "image/jpeg": $kit = ".jpeg"; break;
            default: $kit = ".jpg";
        }

        $foto = date("U").$kit;
        //$unix = date("U");
       // print_r($unix);
       //$uniqId = uniqid();
      // print_r($uniqId);

      //hibaüzenetek összeállítása
      if(isset($hibak)){
        $kimenet = "<ul>\n";
        foreach($hibak as $hiba){
            $kimenet .= "<li>{$hiba}</li>";
        }
        $kimenet .= "</ul>\n";
      }else{
        //felvitel az adatbázisba
        require "../connect.php";
        $sql = "INSERT INTO nevjegyek
                (foto, nev, cegnev, mobil, email)
                VALUES 
                ('{$foto}', '{$nev}','{$cegnev}', '{$mobil}','{$email}' )";

                mysqli_query($dbconn, $sql);

            //kép mozgatása
            move_uploaded_file($_FILES['foto']['tmp_name'], "../kepek/{$foto}");

      }
   

}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Új névjegy felvitele</title>
</head>
<body>
    <h1>Névjegy felvitele</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <!--hibaüzenetek a usernek, ha valamit rosszul tölt ki-->
        <?php if(isset($kimenet)) print $kimenet; ?>

        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <p><label for="foto">Fotó:</label></p>
        <input type="file" name="foto" id="foto">

        <p><label for="nev">Név: *</label></p>
        <input type="text" name="nev" id="nev" value="<?php if(isset($nev)) print $nev;?> ">

        <p><label for="cegnev">Cég név: *</label></p>
        <input type="text" name="cegnev" id="cegnev" value="<?php if(isset($cegnev)) print $cegnev;?> ">

        <p><label for="mobil">Telefon: *</label></p>
        <input type="tel" name="mobil" id="mobil" value="<?php if(isset($mobil)) print $mobil;?> ">

        <p><label for="email">Email: *</label></p>
        <input type="email" name="email" id="email" value="<?php if(isset($email)) print $email;?> ">

        <p><em>*-al jelöl mezők kitöltése kötelező</em></p>

        <input type="submit" value="Rendben" name="rendben" id="rendben">
        <input type="reset" value="Mégsem">
    
    </form>
</body>
</html>