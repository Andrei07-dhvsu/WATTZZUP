<?php
require_once 'superadmin-class.php';
$superadmin = new SUPERADMIN();

if(!$superadmin->isUserLoggedIn())
{
 $superadmin->redirect('../../../');
}

if($superadmin->isUserLoggedIn()!="")
{
 $superadmin->logout();
 $superadmin->redirect('../../../');
}
?>