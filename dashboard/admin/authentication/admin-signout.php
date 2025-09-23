<?php
require_once 'admin-class.php';
$superadmin = new ADMIN();

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