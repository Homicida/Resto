<?php
	require_once("pdo_conf.php");
	
	try {
		if (isset($_POST['currPost'])){
			// Otsib v�lja tabelis eelneva ja j�rgneva postituste ID'd
			$currPost = $_POST['currPost'];
			
			$stmt = $yhendus->query("DELETE FROM postitused WHERE post_ID = '$currPost'");
				 
			$stmt->execute();
		}
		
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
    }
	$yhendus = null;
?>