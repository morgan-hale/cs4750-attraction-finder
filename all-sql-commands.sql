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

CREATE TABLE IF NOT EXISTS AF_User (
    user_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(255) NOT NULL,
    pass_id INT NOT NULL,
    FOREIGN KEY (pass_id) REFERENCES AF_Password(pass_id)
);

CREATE TABLE IF NOT EXISTS AF_Attraction (
    attraction_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    attraction_name VARCHAR(255) NOT NULL,
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    creator_id INT NOT NULL,
    FOREIGN KEY (street_address, city) REFERENCES AF_Location(street_address, city),
    FOREIGN KEY (creator_id) REFERENCES AF_User(user_id)
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

-- DROPPING ALL TABLES 
DROP TABLE IF EXISTS AF_AttractionPhone;
DROP TABLE IF EXISTS AF_Attraction_Has_Type;
DROP TABLE IF EXISTS AF_AttractionType;
DROP TABLE IF EXISTS AF_CustomerPrice;
DROP TABLE IF EXISTS AF_Rating;
DROP TABLE IF EXISTS AF_Favorite;
DROP TABLE IF EXISTS AF_Attraction;
DROP TABLE IF EXISTS AF_User;
DROP TABLE IF EXISTS AF_Password;
DROP TABLE IF EXISTS AF_Location;

-- INSERTING DATA --
INSERT INTO AF_Password (pass_hash) VALUES ('33830b8b7fd414b12c208c4de5055464'), ('8c633d3a9932e6f869a9e56cf87a9a23'), ('77f4de0c4db55dec736561ac64c7ea6b');
INSERT INTO AF_User (username, pass_id) VALUES ('RebeccaNurse', 1), ('PercyJackson', 2), ('PaulAtreides', 3);
INSERT INTO AF_AttractionType (attraction_type_name) VALUES ('Hike'), ('Restaurant'), ('Theme Park'), ('Outdoor Activity'), ('Indoor Activity');
INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('1435 Carters Mountain Trail', 'Charlottesville', 'VA', '22902'), ('Humpback Gap Overlook', 'Afton','VA', '22920'), ('1 Busch Gardens Blvd', 'Williamsburg', 'VA', '23185'), ('375 Merchant Walk Sq', 'Charlottesville', 'VA', '22902'), ('6550 Roseland Farm', 'Crozet', 'VA', '22932');
INSERT INTO AF_Attraction (attraction_name, street_address, city, creator_id) VALUES ('Carter Mountain Sunset Series', '1435 Carters Mountain Trail', 'Charlottesville', 1), ('Carter Mountain Fruit Picking', '1435 Carters Mountain Trail', 'Charlottesville', 1), ('Busch Gardens Williamsburg', '1 Busch Gardens Blvd', 'Williamsburg', 2), ('Alamo Drafthouse Cinema Charlottesville', '375 Merchant Walk Sq', 'Charlottesville', 3), ('Sunday Polo at King Family Vineyard', '6550 Roseland Farm', 'Crozet', 3), ('Humpback Rocks Hike', 'Humpback Gap Overlook', 'Afton', 3) ;
INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (11, 6), (14, 6), (12, 1), (13, 3), (14, 1), (14, 2), (15, 4), (14, 5);
INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('1180 Seven Seas Dr', 'Orlando', 'FL', '32830');
INSERT INTO AF_Attraction (attraction_name, street_address, city, creator_id) VALUES ('Walt Disney World Orlando: Magic Kingdom', '1180 Seven Seas Dr', 'Orlando', 2);
INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (13, 7);
INSERT INTO AF_AttractionPhone (phone, label, attraction_id) VALUES ('434-977-1833', 'General', 1), ('434-977-1833', 'General', 2), ('757-229-4386', 'General', 3), ('434-823-7800', 'General', 5), ('434-325-5056', 'General', 4), ('407-934-7639', 'New and Existing Tickets, Website Support', 7), ('407-939-7529', 'Tours, Recreation', 7), ('407-939-7277', 'Annual Passholders', 7);
INSERT INTO AF_CustomerPrice (attraction_id, customer_type, amount) VALUES (1, 'Children (under 12)', 0), (1, 'Adults (12+)', 10), (2, 'Children (under 12)', 0), (2, 'Adults (12+)', 8), (3, 'Single-Day', 92.99), (4, 'Adults', 12.75), (4, 'Seniors (55+), Children (under 12), and College Students', 10.50), (5, 'Everyone', 0), (6, 'Everyone', 0), (7, 'Single-Day', 109);
INSERT INTO AF_Favorite (user_id, attraction_id) VALUES (1, 1), (1, 2), (1, 4), (2, 3), (2, 7), (3, 6), (3,5);
INSERT INTO AF_Rating (user_id, attraction_id, rating_value) VALUES (1, 1, 5), (1, 2, 5), (1, 4, 5), (2, 3, 5), (2, 7, 5), (3, 6, 5), (3, 5, 5), (2, 1, 3), (2, 2, 3), (3, 4, 1);

INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('200 Epcot Center Dr', 'Lake Buena Vista', 'FL', '32821'), ('351 S Studios Dr', 'Lake Buena Vista', 'FL','32830'), ('2901 Osceola Pkwy', 'Lake Buena Vista', 'FL', '32830'), ('18300 W Alameda Pkwy', 'Morrison', 'CO', '80465'), ('6000 Universal Blvd', 'Orlando', 'FL', '32819'), ('10th St. & Constitution Ave. NW', 'Washington', 'DC', '20560'), ('1805 N. 30th Street', 'Colorado Springs', 'CO', '80904'), ('3001 Connecticut Ave NW', 'Washington', 'DC', '20008'), ('1050 Monticello Loop', 'Charlottesville', 'VA', '22902');
INSERT INTO AF_Attraction (attraction_name, street_address, city, creator_id) VALUES ('Walt Disney World Orlando: EPCOT','200 Epcot Center Dr', 'Lake Buena Vista', 2 ), ('Walt Disney World Orlando: Hollywood Studios', '351 S Studios Dr', 'Lake Buena Vista', 2), ('Walt Disney World Orlando: Animal Kingdom', '2901 Osceola Pkwy', 'Lake Buena Vista', 2), ('Red Rocks Park & Amphitheatre','18300 W Alameda Pkwy', 'Morrison', 3), ('Universal Studios Florida', '6000 Universal Blvd', 'Orlando', 2), ('Smithsonian  National Museum of Natural History', '10th St. & Constitution Ave. NW', 'Washington', 1), ('Garden of the Gods Park', '1805 N. 30th Street', 'Colorado Springs', 3), ('National Zoo', '3001 Connecticut Ave NW', 'Washington', 3), ('Thomas Jefferson Monticello', '1050 Monticello Loop', 'Charlottesville', 1);
INSERT INTO AF_CustomerPrice (attraction_id, customer_type, amount) VALUES (8, 'Single-Day', 109), (9, 'Single-Day', 109), (10, 'Single-Day', 109), (11, 'Everyone', 0), (12, 'Single-Day', 119), (13, 'Everyone', 0), (14, 'Everyone', 0), (15, 'Everyone', 0), (16, 'Children (under 5)', 0), (16, 'Children (5-11)', 4), (16, 'Children (12-18)', 13), (16, 'Adults', 42);
INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (13, 8), (13, 9), (14, 10), (14, 11), (13, 12), (15, 13), (14, 14), (14, 15), (14, 16), (15, 16) ;
INSERT INTO AF_AttractionPhone (phone, label, attraction_id) VALUES ('720-865-2494', 'General', 11), ('407-363-8000', 'General', 12), ('202-633-1000', 'General', 13), ('719-634-6666', 'General', 14), ('202-633-2614', 'General', 15), ('202-633-4134', 'Zoo Police', 15), ('202-633-3025', 'Education and Volunteer Office', 15), ('434-984-9800', 'General', 16);

INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('155 Rugby Rd', 'Charlottesville', 'VA', '22904'), ('1717 Allied Ln', 'Charlottesville', 'VA', '22903'), ('5898 Free Union Rd', 'Free Union', 'VA', '22940'), ('Gateway Arch', 'St. Louis', 'MO', '63102');
INSERT INTO AF_Attraction (attraction_name, street_address, city, creator_id) VALUES ('Fralin Museum of Art', '155 Rugby Rd', 'Charlottesville', 1), ('Unlocked History Escape Rooms','1717 Allied Ln', 'Charlottesville',1), ('Glass House Winery', '5898 Free Union Rd', 'Free Union', 1), ('Gateway Arch', 'Gateway Arch', 'St. Louis', 2);
INSERT INTO AF_CustomerPrice (attraction_id, customer_type, amount) VALUES (17, 'Everyone', 0), (18, 'Children (under 7)', 0), (18, '5+ players',32), (19, 'Everyone', 0), (20, 'Children (3-15',13), (20, 'Adults (16+)', 17);
INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (15, 17), (15, 18), (12, 19), (14, 19), (15, 19), (15, 20);
INSERT INTO AF_AttractionPhone (phone, label, attraction_id) VALUES ('434-924-3592', 'General', 17), ('434-975-0094', 'General', 19), ('877-982-1410', 'General', 20);

INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('13000 SD-244', 'Keystone', 'SD', '57751'),('683 Thomas Jefferson Pkwy', 'Charlottesville', 'VA', '22902'), ('1835 Broadway St', 'Charlottesville', 'VA', '22902'), ('Historic District', 'St. Augustine', 'FL', '32084'), ('Space Commerce Way', 'Merritt Island', 'FL', '32953');
INSERT INTO AF_Attraction (attraction_name, street_address, city, creator_id) VALUES ('Mount Rushmore National Memorial', '13000 SD-244', 'Keystone', 3), ('Michie Tavern','683 Thomas Jefferson Pkwy', 'Charlottesville', 3), ('Rivanna Trail','1835 Broadway St', 'Charlottesville', 3), ('St. Augustine Historic Downtown','Historic District', 'St. Augustine', 2), ('Kennedy Space Center','Space Commerce Way', 'Merritt Island', 1);
INSERT INTO AF_CustomerPrice (attraction_id, customer_type, amount) VALUES (21, 'Everyone', 0), (22, 'Everyone', 0), (23, 'Everyone', 0), (24, 'Everyone', 0), (25, 'Everyone', 80.25);
INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (14, 21), (12, 22), (11, 23),(14, 24), (15, 24), (15, 25);
INSERT INTO AF_AttractionPhone (phone, label, attraction_id) VALUES ('605-574-2523', 'General', 21), ('434-977-1234', 'General', 22), ('855-433-4210', 'General', 25);

-- SELECT EVERYTHING IN EACH TABLE
SELECT * FROM AF_Attraction;
SELECT * FROM AF_Attraction_Has_Type;
SELECT * FROM AF_AttractionType;
SELECT * FROM AF_AttractionPhone;
SELECT * FROM AF_CustomerPrice;
SELECT * FROM AF_User;
SELECT * FROM AF_Favorite;
SELECT * FROM AF_Rating;
SELECT * FROM AF_Location;
SELECT * FROM AF_Password;

-- GETTING TABLE SCHEMAS AND COUNTS
DESC AF_Attraction;
SELECT COUNT(*) FROM AF_Attraction;

DESC AF_Attraction_Has_Type;
SELECT COUNT(*) FROM AF_Attraction_Has_Type;

DESC AF_AttractionType;
SELECT COUNT(*) FROM AF_AttractionType;

DESC AF_AttractionPhone;
SELECT COUNT(*) FROM AF_AttractionPhone;

DESC AF_CustomerPrice;
SELECT COUNT(*) FROM AF_CustomerPrice;

DESC AF_Password;
SELECT COUNT(*) FROM AF_Password;

DESC AF_User;
SELECT COUNT(*) FROM AF_User;

DESC AF_Favorite;
SELECT COUNT(*) FROM AF_Favorite;

DESC AF_Rating;
SELECT COUNT(*) FROM AF_Rating;

DESC AF_Location;
SELECT COUNT(*) FROM AF_Location;

--BASIC COMMANDS FOR ADDING/UPDATING/DELETING--

--ADDING--
INSERT INTO AF_Password (pass_hash) VALUES ('hashed_password_here');

INSERT INTO AF_Location (street_address, city, state, zip_code) VALUES ('123 Main St', 'City Name', 'State Name', '12345');

INSERT INTO AF_Attraction (attraction_name, street_address, city) VALUES ('Attraction Name', '123 Main St', 'City Name');

INSERT INTO AF_Favorite (user_id, attraction_id) VALUES (user_id_here, attraction_id_here);

INSERT INTO AF_Rating (user_id, attraction_id, rating_value) VALUES (user_id_here, attraction_id_here, rating_value_here);

INSERT INTO AF_AttractionType (attraction_type_name) VALUES ('Type Name');

INSERT INTO AF_Attraction_Has_Type (attraction_type_id, attraction_id) VALUES (attraction_type_id_here, attraction_id_here);

INSERT INTO AF_CustomerPrice (attraction_id, customer_type, amount) VALUES (attraction_id_here, 'Customer Type', 10.50);

INSERT INTO AF_User (username, pass_id) VALUES ('username_here', pass_id_here);

INSERT INTO AF_AttractionPhone (phone, label, attraction_id) VALUES ('123-456-7890', 'Label', attraction_id_here);

--UPDATING--
UPDATE AF_Password SET pass_hash = 'new_hashed_password_here' WHERE pass_id = pass_id_here;

UPDATE AF_Attraction SET attraction_name = 'New Attraction Name' WHERE attraction_id = attraction_id_here;

UPDATE AF_User SET username = 'new_username_here' WHERE user_id = user_id_here;

UPDATE AF_Location SET street_address = 'New Street Address', city = 'New City', state = 'New State', zip_code = 'New Zip Code' WHERE street_address = '123 Main St' AND city = 'City Name';

UPDATE AF_Rating SET rating_value = new_rating_value WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

UPDATE AF_AttractionType SET attraction_type_name = 'New Type Name' WHERE attraction_type_id = attraction_type_id_here;

UPDATE AF_CustomerPrice SET amount = new_amount WHERE attraction_id = attraction_id_here AND customer_type = 'Customer Type';

--FOR THE FAVORITE, ATTRACTION_HAS_TYPE, AND ATTRACTIONPHONE TABLES, IF YOU WANTED TO ADD SOMETHING ELSE IT WOULD REQUIRE EITHER --
--THE ADDING OF A NEW OBJECT IN THAT TABLE OR THE DELETION OF AN OBJECT IN THAT TABLE THEREFORE THERE IS NO NEED FOR UPDATE --
--METHODS FOR THOSE TABLES--

--DELETING--
DELETE FROM AF_Password WHERE pass_id = pass_id_here;

DELETE FROM AF_Location WHERE street_address = '123 Main St' AND city = 'City Name';

DELETE FROM AF_Attraction WHERE attraction_id = attraction_id_here;

DELETE FROM AF_Favorite WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

DELETE FROM AF_Rating WHERE user_id = user_id_here AND attraction_id = attraction_id_here;

DELETE FROM AF_AttractionType WHERE attraction_type_id = attraction_type_id_here;

DELETE FROM AF_Attraction_Has_Type WHERE attraction_type_id = attraction_type_id_here AND attraction_id = attraction_id_here;

DELETE FROM AF_CustomerPrice WHERE attraction_id = attraction_id_here AND customer_type = 'Customer Type';

DELETE FROM AF_User WHERE user_id = user_id_here;

DELETE FROM AF_AttractionPhone WHERE phone = '123-456-7890' AND attraction_id = attraction_id_here;

--SELECTION QUERIES FOR PHP (ALL SELECTION QUERIES THAT NEED PARAMETERS WILL BE GTIVEN PARAMETERS AND SANITIZED IN THE PHP PRODUCT--
--BASIC QUERY FOR FINDING ALL ATTRACTIONS (DEFAULT)--
SELECT * FROM AF_Attraction;

--MORE SPECIALIZED QUERY FOR FINDING FILTERED ATTRACTIONS--
SELECT * FROM AF_Attraction a JOIN Favorite f JOIN Rating r JOIN CustomerPrice cp  WHERE 
a.attraction_name LIKE "example_text%" AND a.street_address LIKE "example_addy&" AND a.city LIKE "example_city%"
AND WHERE f.attraction_id = a.attraction_id AND WHERE r.rating_value >= 3 AND WHERE cp.amount <= 10;

--OTHER MORE SPECIALIZED QUERY FOR WHEN YOU SORT RESULTS ON THE ATTRACTION PAGE (THIS IS AN EXAMPLE, IN FULL PRODUCT WE WOULD HAVE MORE DYNAMIC SQL METHODS--
SELECT * FROM AF_Attraction ORDER BY attraction_name DESC;

--FOR FINDING USER'S INFO FOR THE MY PROFILE PAGE, WILL PROBABLY CHECK AGAINST SESSION OBJECT FOR USER ID--
SELECT * FROM AF_User JOIN Favorite ON User.user_id = Favorite.user_id;

--ADVANCED QUERIES--
--THIS ONE WILL BE USED TO CHECK THE PASSWORD AGAINST THE ONE STORED IN THE DATABASE, IF IT IS FALSE THE USER WILL NOT BE ABLE TO LOG-IN--
DELIMITER //

CREATE PROCEDURE AF_CheckPassword(
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
    SELECT * FROM AF_Attraction;
END //

CREATE TRIGGER AfterAttractionChange
AFTER INSERT ON AF_Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

CREATE TRIGGER AfterAttractionUpdate
AFTER UPDATE ON AF_Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

CREATE TRIGGER AfterAttractionDelete
AFTER DELETE ON AF_Attraction
FOR EACH ROW
BEGIN
    CALL UpdateAttractionData();
END //

DELIMITER ;