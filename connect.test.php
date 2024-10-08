<?php
require_once("connect.php");

if ($dbconn->connect_error) {
  die("Connection failed: " . $dbconn->connect_error);
}
echo "Adatbázis kapcsolat sikeresen létrejött";
