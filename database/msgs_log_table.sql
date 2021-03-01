CREATE TABLE msgs_log(
msg_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
msg_body BLOB NOT NULL,
msg_sendr VARCHAR(225) NOT NULL,
msg_recievr VARCHAR(225) NOT NULL,
doc_related_to VARCHAR(225) NOT NULL,
msg_sent_date VARCHAR(225) NOT NULL,
msg_sent_time VARCHAR(225) NOT NULL
)ENGINE = InnoDB;