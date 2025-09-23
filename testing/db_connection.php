


<?php
	try {

        // Live server connection
		$pdoConnect = new PDO("mysql:host=localhost;dbname=u297724503_irrigation", "u297724503_irrigation", "Irrigation2024$");
		$pdoConnect->setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

	}
	catch (PDOException $exc){
		echo $exc -> getMessage();
	}
    catch (PDOException $exc){
        echo $exc -> getMessage();
    exit();
    }
?>