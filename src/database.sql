

CREATE TABLE User(
    id INT AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullName Varchar (100) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
);
    