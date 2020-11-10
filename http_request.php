<?php
include "Core_finance.php";

$mode = $_GET["MODE"];
$sql = "";
$arrReturn = array();

    switch ($mode) {

        //Registrierung
        case 0:
            $sql = "INSERT INTO USER (VORNAME,NACHNAME,PASSWORD,EMAIL,USER_KENNUNG) VALUES ('".$_GET["VORNAME"]."','".$_GET["NACHNAME"]."','".$_GET["PASSWORT"]."','".$_GET["MAIL"]."','".uniqid()."')";
            break;
        //Login
        case 1:
            $sql = "SELECT * FROM USER WHERE EMAIL ='".$_GET["MAIL"]."' AND PASSWORD='".$_GET["PASSWORT"]."'";
            break;
        //Kontenrundruf
        case 2:
            $arrRundruf = Core_finance::MAKE_KONTENRUNDRUF();

            if($arrRundruf["STATUSCODE"] == 1){
                $arrReturn = Core_finance::RETURN_KONTO_UMSATZE();
            }else{
                $arrReturn["STATUSCODE"] = -1;
                $arrReturn["STATUS"] = "Fehler beim Kontorundruf";
            }

            break;
    }

echo json_encode($arrReturn);

