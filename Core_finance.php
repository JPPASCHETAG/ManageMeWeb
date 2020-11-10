<?php
include "CORE_DB.php";
require 'HBCI/vendor/autoload.php';
use Fhp\FinTs;
use Fhp\Model\StatementOfAccount\Statement;
use Fhp\Model\StatementOfAccount\Transaction;

class Core_finance{

    public static function getKontoUmsaetze(){

        define('FHP_BANK_URL', 'https://fints.ing.de/fints');                # HBCI / FinTS Url can be found here: https://www.hbci-zka.de/institute/institut_auswahl.htm (use the PIN/TAN URL)
        define('FHP_BANK_PORT', 443);              # HBCI / FinTS Port can be found here: https://www.hbci-zka.de/institute/institut_auswahl.htm
        define('FHP_BANK_CODE', '50010517');               # Your bank code / Bankleitzahl
        define('FHP_ONLINE_BANKING_USERNAME', '5418251008'); # Your online banking username / alias
        define('FHP_ONLINE_BANKING_PIN', '54431073');      # Your online banking PIN (NOT! the pin of your bank card!)

        $fints = new FinTs(FHP_BANK_URL,FHP_BANK_PORT,FHP_BANK_CODE,FHP_ONLINE_BANKING_USERNAME,FHP_ONLINE_BANKING_PIN);

        $accounts = $fints->getSEPAAccounts();

        $oneAccount = $accounts[1];
        //@todo: hier muss das letze datum eines Rundrufs geholt werden
        $from = new DateTime('2020-09-10');
        $to   = new DateTime();
        $soa = $fints->getStatementOfAccount($oneAccount, $from, $to);

        $arrStatements = array();
        $i = 0;

        foreach ($soa->getStatements() as $statement) {

            foreach ($statement->getTransactions() as $transaction) {
                $arrStatements[$i]["AMOUNT"] = $transaction->getAmount();
                $arrStatements[$i]["DATE"] = $transaction->getValutaDate();
                $arrStatements[$i]["ART"] = $transaction->getBookingText();
                $arrStatements[$i]["NAME"] = $transaction->getName();
                $arrStatements[$i]["VWZ"] = $transaction->getDescription1();
                $arrStatements[$i]["CREDIT_DEBIT"] = $transaction->getCreditDebit();
                $i++;
            }
        }

        return $arrStatements;
    }

    public static function MAKE_KONTENRUNDRUF(){

        $strGesamtSQL = "";

        //die Kontoumsätze holen
        $arrKonto = self::getKontoUmsaetze();

        foreach ($arrKonto as $umsatz){

            $ValutaDate = date_format($umsatz["DATE"],"Y-m-d");
            $strEinzelSQL = "INSERT INTO KONTO_".CORE_DB::USER_ID ."(BETRAG,VZWECK,ART,NAME,DATE,CREDIT_DEBIT,IS_SORTED) VALUES (".$umsatz["AMOUNT"].",'".$umsatz["VWZ"]."','".$umsatz["ART"]."','".$umsatz["NAME"]."','".$ValutaDate."','".$umsatz["CREDIT_DEBIT"]."',0);";
            $strGesamtSQL .= $strEinzelSQL;
        }

        $oDB = new CORE_DB(1);
        $oDB->setSQL($strGesamtSQL);
        $arrResult = $oDB->RUN_SQL();

        return $arrResult;
    }

    public static function RETURN_KONTO_UMSATZE(){

        $strSQL = "SELECT * FROM KONTO_".CORE_DB::USER_ID ." ORDER BY DATE DESC";

        $oDB = new CORE_DB(0);
        $oDB->setSQL($strSQL);
        $arrResult = $oDB->RUN_SQL();

        return $arrResult;
    }



}
