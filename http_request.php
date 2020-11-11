<?php
include "Core_finance.php";
include "CORE_ANMELDUNG.php";

$mode = $_GET["MODE"];
$sql = "";
$arrReturn = array();

    switch ($mode) {

        //Registrierung
        case 0:
            $arrReturn = CORE_ANMELDUNG::REGISTRIERUNG();
            break;
        //Login
        case 1:
            $arrReturn = CORE_ANMELDUNG::MAKE_LOGIN();
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

