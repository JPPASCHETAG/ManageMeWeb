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

        $strSQL = "INSERT INTO USER (VORNAME,NACHNAME,PASSWORD,EMAIL,USER_KENNUNG) VALUES ('".$_GET["VORNAME"]."','".$_GET["NACHNAME"]."','".$_GET["PASSWORT"]."','".$_GET["MAIL"]."','".uniqid()."')";

        $oDB = new CORE_DB(1);
        $oDB->setSQL($strSQL);
        $arrResult = $oDB->RUN_SQL();

        if($arrResult["STATUSCODE"] == 1){
            $arrResult = POST_EXECUTE::appendSuccessData($arrResult,0);
        }else{
            $arrResult = POST_EXECUTE::appendErrorData($arrResult,0);
        }

        return $arrResult;
    }

}
