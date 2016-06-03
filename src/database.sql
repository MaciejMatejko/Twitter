

CREATE TABLE User(
    id INT AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullName Varchar (100) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
);
   

CREATE TABLE Tweet(
    id INT AUTO_INCREMENT,
    user_id  INT NOT NULL,
    text VARCHAR(140),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES User(id)
    ON DELETE CASCADE
);

CREATE TABLE Comment(
    id INT AUTO_INCREMENT,
    tweet_id  INT NOT NULL,
    user_id INT NOT NULL,
    text VARCHAR(60),
    creation_date DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tweet_id) REFERENCES Tweet(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id)
    ON DELETE CASCADE
);

CREATE TABLE Message(
    id INT NOT NULL AUTO_INCREMENT,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    text TEXT NOT NULL,
    read TINYINT(1) NOT NULL,
    crearion_date DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (sender_id) REFERENCES User(id)
    ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES User(id)
    ON DELETE CASCADE
);