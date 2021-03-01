CREATE TABLE msg_replies(
reply_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
assoc_msg_id INT NOT NULL,
reply_body BLOB NOT NULL
)ENGINE = InnoDB;