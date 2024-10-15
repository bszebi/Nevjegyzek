<?php 

require "../connect.php";
//képfájlok megengedett típusainak tárolása
$mime = ["image/jpeg", "image/jpg", "image/gif", "image/png"];
//2mb maximális file méret
$maxFileSize = 2000000;


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

    $hibak = [];
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

   if($_FILES['foto']['error'] == 0){
    if($_FILES['foto']['size'] > $maxFileSize){
        $hibak[] = "Túl nagy méretű képet tölt fel, max 2MB";
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['foto']['tmp_name'] );
    finfo_close($finfo);
    if(!in_array($mimeType, $mime)){
        $hibak[] = 'nem megfelelő kép formátum';
    
    }
    if(empty($hibak)){
        switch($mimeType){
            case "image/png": $kit = ".png"; break;
            case "image/gif": $kit = ".gif"; break;
            case "image/jpeg": $kit = ".jpeg"; break;
            default: $kit = ".jpg";
        }
        $foto = date("U").$kit;
    }
}
else{
    $foto = $_POST["regi_foto"];
}
        //kép nevének összeállítása


        //$unix = date("U");
       // print_r($unix);
       //$uniqId = uniqid();
      // print_r($uniqId);

      //hibaüzenetek összeállítása
    if(!empty($hibak)){
        $kimenet = "<ul>\n";
        foreach($hibak as $hiba){
            $kimenet .= "<li>{$hiba}</li>";
        }
        $kimenet .= "</ul>\n";
    }else{
        if($_FILES['foto']['error'] == 0 && empty($hibak)){
            move_uploaded_file($_FILES['foto']['tmp_name'], "../kepek/{$foto}");
        }
        //adatbázis frissitése
        $id = (int)$_GET["id"];

        $sql = "UPDATE nevjegyek
            SET 
            foto = '{$foto}',nev = '{$nev}', cegnev = '{$cegnev}', mobil = '{$mobil}'
            , email = '{$email}' WHERE id = {$id} ";
            if(mysqli_query($dbconn, $sql)){
                $kimenet = "adatok sikeresen frissültek";
            }else{
                $kimenet = "<p style=\"color:red;\" >hiba történt a frissités közben </p>";
        }           
    }
}else{
    $id = (int)$_GET["id"];
    $sql = "SELECT * 
        FROM nevjegyek
        WHERE id = {$id} ";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    $foto = htmlspecialchars($sor['foto'], ENT_QUOTES, 'utf-8');
    $cegnev = htmlspecialchars($sor['cegnev'], ENT_QUOTES, 'utf-8');
    $mobil = htmlspecialchars($sor['mobil'], ENT_QUOTES, 'utf-8');
    $email = htmlspecialchars($sor['email'], ENT_QUOTES, 'utf-8');
    $nev = htmlspecialchars($sor['nev'], ENT_QUOTES, 'utf-8');
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
        <!--foto id nek a tárolása-->
        <input type="hidden" id="regi_foto" name="regi_foto" value="<?php echo $foto; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <!-- jelenlegi fotó megjelenítése -->
        <img src="../kepek/<?php echo $foto ?>" alt="">

        
        <p><label for="foto">Fotó:</label></p>
        <input type="file" name="foto" id="foto">

        <p><label for="nev">Név: *</label></p>
        <input type="text" name="nev" id="nev" value="<?php print $nev;?> ">

        <p><label for="cegnev">Cég név: *</label></p>
        <input type="text" name="cegnev" id="cegnev" value="<?php  print $cegnev;?> ">

        <p><label for="mobil">Telefon: *</label></p>
        <input type="tel" name="mobil" id="mobil" value="<?php  print $mobil;?> ">

        <p><label for="email">Email: *</label></p>
        <input type="email" name="email" id="email" value="<?php  print $email;?> ">

        <p><em>*-al jelöl mezők kitöltése kötelező</em></p>

        <input type="submit" value="Rendben" name="rendben" id="rendben">
        <input type="reset" value="Mégsem">
    
    </form>
</body>
</html>