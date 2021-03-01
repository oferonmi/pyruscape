CREATE TABLE doc_files_details(
doc_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
doc_count INT(1) NOT NULL,
doc_title VARCHAR(200) NOT NULL,
doc_description BLOB NOT NULL,
doc_signatory VARCHAR(60) NOT NULL,
doc_org_name VARCHAR(150) NOT NULL,
doc_dept_name VARCHAR(150) NOT NULL,
doc_assoc_money_amount FLOAT,  
doc_file_path VARCHAR,
doc_upload_time TIMESTAMP NOT NULL,
user_email VARCHAR,
user_password VARCHAR,
doc_status VARCHAR,
doc_class VARCHAR
)ENGINE = InnoDB;