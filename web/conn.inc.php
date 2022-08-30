<?php


function conn_db()
{

    $host = 'ih1o2.myd.infomaniak.com';
    $base = "ih1o2_pi02db1";
    $user = "ih1o2_pi02db1";
    $pass = "P_XUhBsKxM0";


	$dsn='mysql:host='.$host.';dbname='.$base.';charset=UTF8';
    try
    {

        $dbh = new PDO($dsn, $user, $pass);
        /*** les erreurs sont gérées par des exceptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }
    catch (PDOException $e)
    {
        print "erreur ! :". $e->getMessage()."<br/>";
        die();
    }
}


