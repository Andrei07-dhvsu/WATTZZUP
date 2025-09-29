<?php
require_once 'admin-class.php';
$admin = new ADMIN();

if(!$admin->isUserLoggedIn())
{
 $admin->redirect('../../../');
}

if($admin->isUserLoggedIn()!="")
{
 $admin->logout();
 $admin->redirect('../../../');
}
?>