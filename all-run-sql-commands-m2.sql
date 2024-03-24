-- This file contains all the SQL commands that have been run against our database for Milestone 2. 
-- It is a subset of the all-sql-commands.sql file which contains both used and future sql commands.

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