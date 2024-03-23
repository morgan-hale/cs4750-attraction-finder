-- this creates auto integer PK with auto_increment 
-- I just copied and pasted this into CS database SQL box to create the table
CREATE TABLE requests(reqId INT AUTO_INCREMENT, 
                      reqDate DATE NOT NULL,
                      roomNumber VARCHAR(30), 
                      reqBy VARCHAR(60) NOT NULL,
                      repairDesc VARCHAR(255) NOT NULL,
                      reqPriority VARCHAR(10),
                      PRIMARY KEY (reqId));


 -- drop table code to use if desired
 DROP TABLE IF EXISTS requests;
