<?php
include "postExecute.php";

class CORE_ANMELDUNG{

    public static function MAKE_LOGIN(){

        $strSQL = "SELECT * FROM USER WHERE EMAIL ='".$_GET["MAIL"]."' AND PASSWORD='".$_GET["PASSWORT"]."'";

        $oDB = new CORE_DB(0);
        $oDB->setSQL($strSQL);
        $arrResult = $oDB->RUN_SQL();

        if($arrResult["STATUSCODE"] == 1){
            $arrResult = POST_EXECUTE::appendSuccessData($arrResult,1);
        }else{
            $arrResult = POST_EXECUTE::appendErrorData($arrResult,1);
        }

        return $arrResult;
    }

    public static function REGISTRIERUNG(){

        $userKennung = uniqid();

        $strSQL = "INSERT INTO USER (VORNAME,NACHNAME,PASSWORD,EMAIL,USER_KENNUNG) VALUES ('".$_GET["VORNAME"]."','".$_GET["NACHNAME"]."','".$_GET["PASSWORT"]."','".$_GET["MAIL"]."','".$userKennung."')";

        $oDB = new CORE_DB(1);
        $oDB->setSQL($strSQL);
        $arrResult = $oDB->RUN_SQL();

        if($arrResult["STATUSCODE"] == 1){
            $arrResult = POST_EXECUTE::appendSuccessData($arrResult,0);
            self::CREATE_DATABASES($userKennung);
        }else{
            $arrResult = POST_EXECUTE::appendErrorData($arrResult,0);
        }

        return $arrResult;
    }

    public static function CREATE_DATABASES($userKennung){

        $strSQL_KONTO = "CREATE TABLE KONTO_".$userKennung." (
                          `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `BETRAG` float unsigned NOT NULL DEFAULT 0,
                          `VZWECK` varchar(150) NOT NULL DEFAULT '',
                          `ART` varchar(50) NOT NULL DEFAULT '',
                          `NAME` varchar(50) NOT NULL DEFAULT '',
                          `DATE` date DEFAULT current_timestamp(),
                          `CREDIT_DEBIT` varchar(50) NOT NULL DEFAULT '',
                          `IS_SORTED` tinyint(1) DEFAULT 0,
                          PRIMARY KEY (`ID`)
                        ) ENGINE=InnoDB";

        $oDB = new CORE_DB(1);
        $oDB->setSQL($strSQL_KONTO);
        $oDB->RUN_SQL();

        $strSQL_Projekte = "CREATE TABLE PROJEKTE_".$userKennung." (
                          `PROJEKT_ID` int(11) unsigned NOT NULL,
                          `BEZ` varchar(50) NOT NULL DEFAULT '',
                          `TABELLE` varchar(50) NOT NULL DEFAULT '',
                          `ERSTELLDATUM` date NOT NULL DEFAULT current_timestamp(),
                          `ERSTELLER` int(11) NOT NULL,
                          PRIMARY KEY (`PROJEKT_ID`)
                        ) ENGINE=InnoDB";

        $oDB = new CORE_DB(1);
        $oDB->setSQL($strSQL_Projekte);
        $oDB->RUN_SQL();


    }

}
