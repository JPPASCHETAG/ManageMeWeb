<?php
class CORE_DB{

    private $mysql;
    private $sql = "";
    private $CONNECTION_STATUS = 1;
    private $SQL_ART = 0;
    const USER_ID = "5f79aeaa55946";

    /**
     * CORE_DB constructor.
     * Mode O: select
     * Mode 1: insert/update
     *
     * @param $SQL_ART
     */
    public function __construct($SQL_ART){
        $this->SQL_ART = $SQL_ART;
        $this->mysql = new mysqli("localhost","root","","manage_me");
        if (mysqli_connect_errno())
        {
            $this->CONNECTION_STATUS = -1;
        }
        //@TODO USER_ID sezen

    }

    public function setSQL($strSQL){
        $this->sql = $strSQL;
    }

    public function RUN_SQL(){

        $arrReturn = Array();

        if($this->CONNECTION_STATUS == 1 && $this->sql != ""){

            $rows = 0;

            if ($this->mysql->multi_query($this->sql)) {
                do {
                    /* store first result set */
                    if ($result = $this->mysql->store_result()) {

                        while ($row = $result->fetch_assoc()) {
                            array_push($arrReturn, $row);
                        }
                        $rows .= $result->num_rows;
                        $result->free();
                    }

                } while ($this->mysql->next_result());

                if($rows > 0 || $this->SQL_ART == 1){
                    $arrReturn["STATUSCODE"] = 1;
                }else{
                    $arrReturn["STATUSCODE"] = -1;
                }

            }else{
                $arrReturn["ERROR_CODE"] = $this->mysql->errno;
                $arrReturn["STATUSCODE"] = -1;
            }
        }
        return $arrReturn;
    }

}
