<?php
define("DBHOST","localhost");
define("DBUSER","root");
define("DBPASS","");
define("DBNAME","nevjegyzek");

$dbconn = @mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME) or die("Hiba az adatbázis csatlakozásakor!");

mysqli_query($dbconn, "SET NAMES utf8");