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
            $date = "";
            if(isset($_GET["LAST_RUNDRUF"])){
                $date = @$_GET["LAST_RUNDRUF"];
            }
            if($date == ""){
                $date = new DateTime('-90 days');
                $date = $date->format('Y-m-d');
            }

            if(isset($_GET["ONLINE_BANKING_NUTZER"]) && isset($_GET["ONLINE_BANKING_PWD"]) && isset($_GET["USER_KENNUNG"])) {
                $HBCI_Nutzer = $_GET["ONLINE_BANKING_NUTZER"];
                $HbCI_Pw = $_GET["ONLINE_BANKING_PWD"];
                $userKennung = $_GET["USER_KENNUNG"];

                $arrRundruf = Core_finance::MAKE_KONTENRUNDRUF($date,$HBCI_Nutzer,$HbCI_Pw,$userKennung);

                if ($arrRundruf["STATUSCODE"] == 1) {
                    $arrReturn = Core_finance::RETURN_KONTO_UMSATZE($date,$userKennung);
                } else {
                    $arrReturn["STATUSCODE"] = -1;
                    $arrReturn["STATUS"] = "Fehler beim Kontorundruf";
                }
            }else{
                $arrReturn["STATUSCODE"] = -1;
                $arrReturn["STATUS"] = "Fehler beim Kontorundruf";
            }
            break;
    }

echo json_encode($arrReturn);

