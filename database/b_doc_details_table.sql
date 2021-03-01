CREATE TABLE b_doc_details(
b_doc_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
b_doc_count INT(1) NOT NULL,
b_doc_title VARCHAR(255) NOT NULL UNIQUE,
b_doc_descriptn
b_doc_author
b_doc_path
b_doc_uploadtime
b_doc_likes
b_doc_dislikes
b_doc_viewsCount
bcomments_count
user_email
user_password
)ENGINE = InnoDB;