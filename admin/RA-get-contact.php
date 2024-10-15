<?php 
require_once('../connect.php');
header('Content-Type: application/json');
//ha van id a get kérésben, le tudunk kérni adatokat

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    //névjegy lekérdezés id alapján;
    $sql = "SELECT * FROM nevjegyek WHERE id = ?";
    
    $stmt = $dbconn -> prepare($sql);

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result -> num_rows>0){
        $contact = $result -> fetch_assoc();
        echo json_encode($contact, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);
    }
    else{
        http_response_code(404);
        echo json_encode(['error' => 'névjegy nem található'], JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);
    }
} else{
    $sql = "SELECT * FROM nevjegyek";
    $result = $dbconn -> query($sql);
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row; 
    }
    echo json_encode($contacts, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT); 
}