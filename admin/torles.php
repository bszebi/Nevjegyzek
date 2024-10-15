<?php 

if(isset($_GET['id'])) {
    print_r($_GET['id']);
    require "../connect.php";
    $id = (int)$_GET['id'];

    $sql = "SELECT foto 
            FROM nevjegyek 
            WHERE id = {$id}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    var_dump($sor);
    if ($sor['foto']) {
        unlink("../kepek/{$sor['foto']}");

    }
    $sql = "DELETE FROM nevjegyek WHERE id = {$id}";
    mysqli_query($dbconn, $sql);
    


}
    
    header('location: lista.php');
    ?>