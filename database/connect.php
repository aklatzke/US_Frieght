<?php
$environment = 'local';



if($environment == 'local'){
	$db = new PDO('mysql:host=localhost;dbname=USFreight;port=8888;charset=utf8', 'root', 'root');
}

if($environment == 'live'){

}
?>
