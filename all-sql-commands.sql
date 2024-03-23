-- TABLE CREATION SQL--
CREATE TABLE IF NOT EXISTS AF_Password (
    pass_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    pass_hash VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS AF_Location (
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zip_code VARCHAR(255) NOT NULL,
    PRIMARY KEY (street_address, city)
);

CREATE TABLE IF NOT EXISTS AF_Attraction (
    attraction_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    attraction_name VARCHAR(255) NOT NULL,
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    FOREIGN KEY (street_address, city) REFERENCES AF_Location(street_address, city)
);

CREATE TABLE IF NOT EXISTS AF_User (
    user_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(255) NOT NULL,
    pass_id INT NOT NULL,
    FOREIGN KEY (pass_id) REFERENCES AF_Password(pass_id)
);

CREATE TABLE IF NOT EXISTS AF_Favorite (
    user_id INT NOT NULL,
    attraction_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES AF_User(user_id),
    FOREIGN KEY (attraction_id) REFERENCES AF_Attraction(attraction_id),
    PRIMARY KEY (user_id, attraction_id)
);

CREATE TABLE IF NOT EXISTS AF_Rating (
    user_id INT NOT NULL,
    attraction_id INT NOT NULL,
    rating_value INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES AF_User(user_id),
    FOREIGN KEY (attraction_id) REFERENCES AF_Attraction(attraction_id),
    PRIMARY KEY (user_id, attraction_id)
);

CREATE TABLE IF NOT EXISTS AF_AttractionType (
    attraction_type_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    attraction_type_name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS AF_Attraction_Has_Type (
    attraction_type_id INT NOT NULL,
    attraction_id INT NOT NULL,
    FOREIGN KEY (attraction_type_id) REFERENCES AF_AttractionType(attraction_type_id),
    FOREIGN KEY (attraction_id) REFERENCES AF_Attraction(attraction_id),
    PRIMARY KEY (attraction_type_id, attraction_id)
);

CREATE TABLE IF NOT EXISTS AF_CustomerPrice (
    attraction_id INT NOT NULL,
    customer_type VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (attraction_id) REFERENCES AF_Attraction(attraction_id),
    PRIMARY KEY (attraction_id, customer_type)
);

CREATE TABLE IF NOT EXISTS AF_AttractionPhone (
    phone VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    attraction_id INT NOT NULL,
    FOREIGN KEY (attraction_id) REFERENCES AF_Attraction(attraction_id),
    PRIMARY KEY (phone, attraction_id)
);


--BASIC COMMANDS FOR ADDING/UPDATING/DELETING--

--ADDING--
INSERT INTO Password (pass_hash) VALUES ('hashed_password_here');

INSERT INTO Location (street_address, city, state, zip_code) VALUES ('123 Main St', 'City Name', 'State Name', '12345');

INSERT INTO Attraction (attraction_name, street_address, city) VALUES ('Attraction Name', '123 Main St', 'City Name');

INSERT INTO Favorite (user_id, attraction_id) VALUES (user_id_here, attraction_id_here);

INSERT INTO Rating (user_id, attraction_id, rating_value) VALUES (user_id_here, attraction_id_here, rating_value_here);

INSERT INTO AttractionType (attraction_type_name) VALUES ('Type Name');

INSERT INTO Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (attraction_type_id_here, attraction_id_here);

INSERT INTO CustomerPrice (attraction_id, customer_type, amount) VALUES (attraction_id_here, 'Customer Type', 10.50);

INSERT INTO User (username, pass_id) VALUES ('username_here', pass_id_here);

INSERT INTO AttractionPhone (phone, label, attraction_id) VALUES ('123-456-7890', 'Label', attraction_id_here);

--UPDATING--
UPDATE Password SET pass_hash = 'new_hashed_password_here' WHERE pass_id = pass_id_here;

UPDATE Attraction SET attraction_name = 'New Attraction Name' WHERE attraction_id = attraction_id_here;

UPDATE User SET username = 'new_username_here' WHERE user_id = user_id_here;

UPDATE Location SET street_address = 'New Street Address', city = 'New City', state = 'New State', zip_code = 'New Zip Code' WHERE street_address = '123 Main St' AND city = 'City Name';

UPDATE Rating SET rating_value = new_rating_value WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

UPDATE AttractionType SET attraction_type_name = 'New Type Name' WHERE attraction_type_id = attraction_type_id_here;

UPDATE CustomerPrice SET amount = new_amount WHERE attraction_id = attraction_id_here AND customer_type = 'Customer Type';

--FOR THE FAVORITE, ATTRACTION_HAS_TYPE, AND ATTRACTIONPHONE TABLES, IF YOU WANTED TO ADD SOMETHING ELSE IT WOULD REQUIRE EITHER --
--THE ADDING OF A NEW OBJECT IN THAT TABLE OR THE DELETION OF AN OBJECT IN THAT TABLE THEREFORE THERE IS NO NEED FOR UPDATE --
--METHODS FOR THOSE TABLES--

--DELETING--
DELETE FROM Password WHERE pass_id = pass_id_here;

DELETE FROM Location WHERE street_address = '123 Main St' AND city = 'City Name';

DELETE FROM Attraction WHERE attraction_id = attraction_id_here;

DELETE FROM Favorite WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

DELETE FROM Rating WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

DELETE FROM AttractionType WHERE attraction_type_id = attraction_type_id_here;

DELETE FROM Attraction_Has_Type WHERE attraction_type_id = attraction_type_id_here AND attraction_id = attraction_id_here;

DELETE FROM CustomerPrice WHERE attraction_id = attraction_id_here AND customer_type = 'Customer Type';

DELETE FROM User WHERE user_id = user_id_here;

DELETE FROM AttractionPhone WHERE phone = '123-456-7890' AND attraction_id = attraction_id_here;

--SELECTION QUERIES FOR PHP (ALL SELECTION QUERIES THAT NEED PARAMETERS WILL BE GTIVEN PARAMETERS AND SANITIZED IN THE PHP PRODUCT--
--BASIC QUERY FOR FINDING ALL ATTRACTIONS (DEFAULT)--
SELECT * FROM Attraction;

--MORE SPECIALIZED QUERY FOR FINDING FILTERED ATTRACTIONS--
SELECT * FROM Attraction a JOIN Favorite f JOIN Rating r JOIN CustomerPrice cp  WHERE 
a.attraction_name LIKE "example_text%" AND a.street_address LIKE "example_addy&" AND a.city LIKE "example_city%"
AND WHERE f.attraction_id = a.attraction_id AND WHERE r.rating_value >= 3 AND WHERE cp.amount <= 10;

--OTHER MORE SPECIALIZED QUERY FOR WHEN YOU SORT RESULTS ON THE ATTRACTION PAGE (THIS IS AN EXAMPLE, IN FULL PRODUCT WE WOULD HAVE MORE DYNAMIC SQL METHODS--
SELECT * FROM Attraction ORDER BY attraction_name DESC;

--FOR FINDING USER'S INFO FOR THE MY PROFILE PAGE, WILL PROBABLY CHECK AGAINST SESSION OBJECT FOR USER ID--
SELECT * FROM User JOIN Favorite ON User.user_id = Favorite.user_id;

--ADVANCED QUERIES--
--THIS ONE WILL BE USED TO CHECK THE PASSWORD AGAINST THE ONE STORED IN THE DATABASE, IF IT IS FALSE THE USER WILL NOT BE ABLE TO LOG-IN--
DELIMITER //

CREATE PROCEDURE CheckPassword(
    IN p_username VARCHAR(255),
    IN p_password_hash VARCHAR(255),
    OUT p_password_matched BOOLEAN
)
BEGIN
    DECLARE db_password_hash VARCHAR(255);

    SELECT pass.pass_hash INTO db_password_hash
    FROM User
    JOIN Password pass ON User.pass_id = pass.pass_id
    WHERE User.username = p_username;

    IF db_password_hash IS NOT NULL AND db_password_hash = p_password_hash THEN
        SET p_password_matched = TRUE;
    ELSE
        SET p_password_matched = FALSE;
    END IF;
END //

DELIMITER ;

--THIS SQL PROCEDURE AND TRIGGERS WILL UPDATE THE TABLE WHILE ON PAGE AFTER AN INSERT/UPDATE/DELETION--

DELIMITER //

CREATE PROCEDURE UpdateAttractionData()
BEGIN
    SELECT * FROM Attraction;
END //

CREATE TRIGGER AfterAttractionChange
AFTER INSERT ON Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

CREATE TRIGGER AfterAttractionUpdate
AFTER UPDATE ON Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

CREATE TRIGGER AfterAttractionDelete
AFTER DELETE ON Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

DELIMITER ;