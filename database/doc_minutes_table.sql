CREATE TABLE doc_minutes(
user_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
doc_title VARCHAR(200) NOT NULL,
sig_email VARCHAR(225) NOT NULL,
minute_on_doc BLOB NOT NULL,
minute_author_email VARCHAR(150) NOT NULL,
minute_respondant_email VARCHAR(150) NOT NULL,
time_of_dis VARCHAR(225) NOT NULL,
date_of_dis VARCHAR(225) NOT NULL,
)ENGINE = InnoDB;