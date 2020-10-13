<?php
include ("postExecute.php");
$mysqli = new mysqli("localhost","root","","accounts");

$mode = $_GET["MODE"];
$sql = "";

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    return;
}else {
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
            Core_finance::MAKE_KONTENRUNDRUF();
            break;
    }
}
// Confirm there are results
$arrReturn = Array();
$rows = 0;

if ($mysqli->multi_query($sql))
{
    do {
        /* store first result set */
        if ($result = $mysqli->store_result()) {

            while ($row = $result->fetch_assoc()) {
                array_push($arrReturn,$row);
            }
            $rows .= $result->num_rows;
            $result->free();
        }

    } while ($mysqli->next_result());

    //Auch wenn kein ergebnis zurückgegeben wird den error zurückgeben
    if($rows > 0){
        $arrReturn = POST_EECUTE::appendSuccessData($arrReturn,$mode);
    }else{
        $arrReturn = POST_EECUTE::appendErrorData($arrReturn,$mode);
    }
}else{
    $arrReturn = POST_EECUTE::appendErrorData($arrReturn,$mode);
    $arrReturn["ERROR_CODE"] = $mysqli->errno;

}

echo json_encode($arrReturn);

