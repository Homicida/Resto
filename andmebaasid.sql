CREATE TABLE bronn(
	bronn_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	arv INT(3),
	nimi VARCHAR(50),
	email VARCHAR(50),
	telefon VARCHAR(50),
	kuupaev VARCHAR(10),
	aeg VARCHAR(10),
	lisa TEXT,
	confirmed TINYINT (1) DEFAULT 0
);