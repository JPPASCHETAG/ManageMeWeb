<?php

class POST_EXECUTE{

    public static function appendSuccessData($arrReturn,$mode){

        switch ($mode){
            case 0:
                $arrReturn["REGISTER_SUCCESS"] = true;
                $arrReturn["MAIL"] = $_GET["MAIL"];
                $arrReturn["PASSWORT"] = $_GET["PASSWORT"];
                break;
            case 1:
                $arrReturn["LOGIN_SUCCESS"] = true;
                break;
        }

        return $arrReturn;
    }

    public static function appendErrorData($arrReturn,$mode){

        switch ($mode){
            case 0:
                $arrReturn["REGISTER_SUCCESS"] = false;
                break;
            case 1:
                $arrReturn["LOGIN_SUCCESS"] = false;
                break;
        }


        return $arrReturn;
    }

}
