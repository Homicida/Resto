<?php
	class User{
		private $connection;
		function __construct($mysqli){
			$this->connection = $mysqli;
		}
		function createUser($create_uname, $create_pw, $create_name, $rights){
			//objekt et saata tagasi kas errori(id,message) v�i success(message)
			$response = new StdClass();
			
			$stmt = $this->connection->prepare("SELECT username FROM users WHERE username = ?");
			$stmt->bind_param("s", $create_uname);
			$stmt->execute();
			if($stmt->fetch()){
				//saadan errori
				$error = new StdClass();
				$error->id = 0;
				$error->message = "Sellise kasutajanimega kasutaja on juba olemas";
				//error responsele k�lge
				$response->error = $error;
				//peale returni koodi ei vaadata enam funktsioonis
				return $response;
			}
			//elmine k�sk kinni
			$stmt->close();
			
			$stmt = $this->connection->prepare("INSERT INTO users (username, password, nimi, rights) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("ssss", $create_uname, $create_pw, $create_name, $rights);
			if($stmt->execute()){
				//salvestas edukalt
				$success = new StdClass();
				$success->message = "Kasutaja loomine �nnestus";
				$response->success = $success;
			}else{
				//kui ei l�inud edukalt saadan errori
				$error = new StdClass();
				$error->id = 2;
				$error->message = "Midagi l�ks katki :".$stmt->error;
				//error responsele k�lge
				$response->error = $error;
			}
			$stmt->close();
			return $response;
		}
		function loginUser($uname, $pw){
			$response = new StdClass();
			$stmt = $this->connection->prepare("SELECT id FROM users WHERE username=?");
			$stmt->bind_param("s", $uname);
			$stmt->execute();
			if(!$stmt->fetch()){
				// saadan tagasi errori
				$error = new StdClass();
				$error->id = 2;
				$error->message = "Sellise kasutajanimega kasutajat ei ole";
				
				//panen errori responsile k�lge
				$response->error = $error;
				// p�rast returni enam koodi edasi ei vaadata funktsioonis
				return $response;
			}
			$stmt->close();
		
			$stmt = $this->connection->prepare("SELECT rights, nimi FROM users WHERE username=? AND password=?");
			$stmt->bind_param("ss", $uname, $pw);
			$stmt->bind_result($rights, $nimi);
			$stmt->execute();
			if($stmt->fetch()){
				$success = new StdClass();
				$success->message = "Sisselogimine �nnestus";
				$user = new StdClass();
				//$user->id = $id_from_db;
				$user->rights = $rights;
				$user->nimi = $nimi;
				//$success->user = $user;
				$success->user = $user;
				$response->success = $success;
			}else{
				$error = new StdClass();
				$error->id = 3;
				$error->message = "Vale parool";
				$response->error = $error;
			}
			$stmt->close();
			return $response;
		}
	}