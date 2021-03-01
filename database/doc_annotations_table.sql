CREATE TABLE doc_annotations(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
doc_title VARCHAR(255) NOT NULL,
annotation VARCHAR(255) NOT NULL,
annotator_email VARCHAR(255) NOT NULL,
annotation_date VARCHAR(255)  NOT NULL,
annotation_time VARCHAR(255) NOT NULL,
)ENGINE = InnoDB;