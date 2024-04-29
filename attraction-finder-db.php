<!-- Functions interacting with the database -->

<?php

// gets attraction with concated locations for search page
function getAllAttractionsWithLocations()
{
    global $db; // don't keep making new database instance. keep using this global variable! 

    // $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code), attraction_type_name FROM AF_Attraction a NATURAL JOIN AF_Location l NATURAL JOIN AF_Attraction_Has_Type ht NATURAL JOIN AF_AttractionType t WHERE a.attraction_id = ht.attraction_id AND ht.attraction_type_id = t.attraction_type_id;";
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location";

    $statement = $db->prepare($query); // just compiles. we don't need to pass in values so just execute! 
    $statement->execute(); 
    $result = $statement->fetchAll(); // fetches all rows in result. just fetch() returns first row. we need to save it to a variable, we'll call it result
    $statement->closeCursor();
    // we need to return the result back to the form 
    return $result; // form will iterate over results and display one row at a time

}

// same as general attraction + location search, but only showing those created by logged in user 
function getAllAttractionsWithLocationsByCreator($curruser)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Location NATURAL JOIN AF_Attraction JOIN AF_User ON AF_Attraction.creator_id = AF_User.user_id WHERE username=:curruser;";
    $statement = $db->prepare($query); 
    $statement->bindValue(':curruser', $curruser);
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 

}

// returns search result after user searches by attraction address via search bar (form)
function searchAttractionByAddress($search_value)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code)  FROM AF_Attraction NATURAL JOIN AF_Location WHERE CONCAT(street_address, ', ', city,', ', state,' ', zip_code) LIKE :search_val;";
    try{
        $statement = $db->prepare($query);
        $concatenatedstring = "%" . $search_value . "%";
        $statement->bindValue(':search_val', $concatenatedstring);
        $statement->execute(); 
        $result = $statement->fetchAll(); 
        $statement->closeCursor();
        return $result; 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }

}

// returns search result after user searches by name via search bar 
function searchAttractionByName($search_value)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location WHERE attraction_name LIKE :search_val;";
    try{
        $statement = $db->prepare($query);
        $concatenatedstring = "%" . $search_value . "%";
        $statement->bindValue(':search_val', $concatenatedstring);
        $statement->execute(); 
        $result = $statement->fetchAll(); 
        $statement->closeCursor();
        return $result; 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }

}

//returns filtered result based on type via a drop down menu
function filterAttractionsByType($type)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code), attraction_type_name FROM AF_Attraction a NATURAL JOIN AF_Location l NATURAL JOIN AF_Attraction_Has_Type ht NATURAL JOIN AF_AttractionType t WHERE a.attraction_id = ht.attraction_id AND ht.attraction_type_id = t.attraction_type_id AND attraction_type_name=:attractionType;";
    try{
        $statement = $db->prepare($query);
        $statement->bindValue(':attractionType', $type);
        $statement->execute(); 
        $result = $statement->fetchAll(); 
        $statement->closeCursor();
        return $result; 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }
}

//returns filtered result based on city via a drop down menu
function filterAttractionsByCity($city)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location WHERE city=:city";
    try{
        $statement = $db->prepare($query);
        $statement->bindValue(':city', $city);
        $statement->execute(); 
        $result = $statement->fetchAll(); 
        $statement->closeCursor();
        return $result; 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }
}

//returns filtered result based on state via a drop down menu
function filterAttractionsByState($state)
{
    global $db; 
    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location WHERE state=:state";
    try{
        $statement = $db->prepare($query);
        $statement->bindValue(':state', $state);
        $statement->execute(); 
        $result = $statement->fetchAll(); 
        $statement->closeCursor();
        return $result; 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }
}

// gets all attractions natural joined with location but not concatenated formatting (not sure if this is actually in use atm though...)
function getAllAttractions()
{
    global $db; 
    $query = "SELECT * FROM AF_Attraction NATURAL JOIN AF_Location";
    $statement = $db->prepare($query); 
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 

}

// attraction insertion function 
function addAttraction($attraction_name, $street_address, $city, $username, $state, $zip_code, $attraction_type, $attraction_type2, $phone_label, $phone_number, $phone_label2, $phone_number2, $cust_type, $attraction_price, $cust_type2, $attracion_price2)
{
    global $db;  

     // adding to location table (if needed) before attraction table 
     $type_id = $attraction_type[4];
     if ($type_id != '') {
        $type_id2 = $attraction_type2[4] + 1;
     }

    //  var_dump($attraction_price);

    //  checking if location already exists 
    $query = "SELECT * FROM AF_Location WHERE street_address=:street_address AND city=:city";
    $statement = $db->prepare($query);
    $statement->bindValue(':street_address',$street_address);
    $statement->bindValue(':city',$city);
    $statement->execute();
    $result = $statement->fetch();

    //  if location is new, insert new location with all the info
    if ($result == null) {
        $query = "INSERT INTO AF_Location(street_address, city, state, zip_code) VALUES (:street_address, :city, :state, :zip_code)";
        $statement = $db->prepare($query);
        $statement->bindValue(':street_address',$street_address);
        $statement->bindValue(':city',$city);
        $statement->bindValue(':state',$state);
        $statement->bindValue(':zip_code',$zip_code);
        $statement->execute();
    }
    // if street location already exists, no change in that table required
   
    // getting userid for associated username 
    $query = "SELECT user_id FROM AF_User WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $userid = $statement->fetch();
    $userid = $userid['user_id'];

    // now inserting into attraction 
    $query = "INSERT INTO AF_Attraction(attraction_name, street_address, city, creator_id) VALUES (:attraction_name, :street_address, :city, :creator_id)";
    try{
        $statement = $db->prepare($query);
        $statement->bindValue(':attraction_name', $attraction_name);
        $statement->bindValue(':street_address', $street_address);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':creator_id', $userid);
        $statement->execute(); 
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }

    $attraction_id = $db->lastInsertId();

    //insert attraction's type into hastype
    $query = "INSERT INTO AF_Attraction_Has_Type(attraction_type_id, attraction_id) VALUES (:type_id, :attraction_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':type_id', $type_id);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->execute();

    //insert attraction's price into hasprice; currently this hard codes customer type until we implement something more flexible
    $query = "INSERT INTO AF_CustomerPrice(attraction_id, customer_type, amount) VALUES (:attraction_id, :cust_type,:attraction_price)";
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_price', $attraction_price);
    $statement->bindValue(':cust_type', $cust_type);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->execute();

    if ($attraction_type2 != '') {
        $query = "INSERT INTO AF_Attraction_Has_Type(attraction_type_id, attraction_id) VALUES (:type_id, :attraction_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':type_id', $type_id2);
        $statement->bindValue(':attraction_id', $attraction_id);
        $statement->execute();    
    }
    if ($cust_type2 != '') {
        $query = "INSERT INTO AF_CustomerPrice(attraction_id, customer_type, amount) VALUES (:attraction_id, :cust_type,:attraction_price)";
        $statement = $db->prepare($query);
        $statement->bindValue(':attraction_price', $attraction_price);
        $statement->bindValue(':cust_type', $cust_type2);
        $statement->bindValue(':attraction_id', $attraction_id);
        $statement->execute();
    }

    if ($phone_label != '') {
        $query = "INSERT INTO AF_AttractionPhone(phone, label, attraction_id) VALUES (:phone, :label, :attraction_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':phone', $phone_number);
        $statement->bindValue(':label', $phone_label);
        $statement->bindValue(':attraction_id', $attraction_id);
        $statement->execute();    
    }

    if ($phone_label2 != '') {
        $query = "INSERT INTO AF_AttractionPhone(phone, label, attraction_id) VALUES (:phone, :label, :attraction_id)";
        $statement = $db->prepare($query);
        $statement->bindValue(':phone', $phone_number2);
        $statement->bindValue(':label', $phone_label2);
        $statement->bindValue(':attraction_id', $attraction_id);
        $statement->execute();    
    }

    $statement->closeCursor(); // release the Cursor you you don't keep using the instance over and over?     

}


// return all attraction information (base table, location, price, phone, typeIDs, type names) for a given attraction ID
function getAttractionById($id)  
{
    global $db;
    $query = "SELECT * FROM AF_Attraction NATURAL JOIN AF_Location WHERE attraction_id=:attraction_id"; // using the prepared statement template name
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetch();  //!! Note difference btw fetch (returns 1 row) and fetchAll (returns all rows)!
    $statement->closeCursor();
    return $result;
}

function getAllAttractionTypes()
{
    global $db; 
    $query = "SELECT * FROM AF_AttractionType";
    $statement = $db->prepare($query); 
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}

// returns pricing table for given attraction ID
function getPricesforAttraction($id)
{
    global $db;
    $query = "SELECT * FROM AF_CustomerPrice WHERE attraction_id=:attraction_id";
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}

// returns phone number table for given attraction ID
function getPhoneNumbersforAttraction($id)
{
    global $db;
    $query = "SELECT * FROM AF_AttractionPhone WHERE attraction_id=:attraction_id";
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}

// updates location table and attraction table for location/name updates
 
function updateAttraction($attraction_id, $attraction_name, $street_address, $city, $state, $zip_code)
{
    global $db;

    // Updating location table before attraction table 

    //  checking if location was changed (address and city are the PK so we'll need to insert a new row if it did)
    $query = "SELECT * FROM AF_Location WHERE street_address=:street_address AND city=:city";
    $statement = $db->prepare($query);
    $statement->bindValue(':street_address',$street_address);
    $statement->bindValue(':city',$city);
    $statement->execute();
    $result = $statement->fetch(); 

    //  if street address or city did change, insert new location with all the info
    if ($result == null) {
        $query = "INSERT INTO AF_Location(street_address, city, state, zip_code) VALUES (:street_address, :city, :state, :zip_code)";
        $statement = $db->prepare($query);
        $statement->bindValue(':street_address',$street_address);
        $statement->bindValue(':city',$city);
        $statement->bindValue(':state',$state);
        $statement->bindValue(':zip_code',$zip_code);
        $statement->execute();
    // if street address and city still exist, can just updated state, zip if needed
    } else {
        $query = "UPDATE AF_Location SET state=:state, zip_code=:zip_code WHERE street_address=:street_address AND city=:city";
        $statement = $db->prepare($query);
        $statement->bindValue(':street_address',$street_address);
        $statement->bindValue(':city',$city);
        $statement->bindValue(':state',$state);
        $statement->bindValue(':zip_code',$zip_code);
        $statement->execute();
    }
       
    // updating attraction table now
    $query = "UPDATE AF_Attraction SET attraction_name=:attraction_name, street_address=:street_address, city=:city WHERE attraction_id=:attraction_id" ; 
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->bindValue(':attraction_name', $attraction_name);
    $statement->bindValue(':street_address', $street_address);
    $statement->bindValue(':city',$city); 
    $statement->execute();
    $statement->closeCursor();
}

// deletes attraction with a given id 
function deleteAttraction($attraction_id)
{
    global $db;
    $query = "DELETE FROM AF_Attraction_Has_Type WHERE attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();

    $query = "DELETE FROM AF_CustomerPrice WHERE attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();

    $query = "DELETE FROM AF_Attraction WHERE attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();
}

// adds user/attraction into favorite table
function favoriteAttraction($attraction_id, $username)
{
    global $db;

     // getting userid for associated username 
     $query = "SELECT user_id FROM AF_User WHERE username=:username";
     $statement = $db->prepare($query);
     $statement->bindValue(':username', $username);
     $statement->execute();
     $userid = $statement->fetch();
     $userid = $userid['user_id'];

    // inserting into Favorites table 
    $query = "INSERT INTO AF_Favorite(user_id, attraction_id) VALUES (:user_id, :attraction_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userid);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();
}

// retrieves all favorited 
function getFavorites($username)
{
    global $db;

     // getting userid for associated username 
     $query = "SELECT user_id FROM AF_User WHERE username=:username";
     $statement = $db->prepare($query);
     $statement->bindValue(':username', $username);
     $statement->execute();
     $userid = $statement->fetch();
     $userid = $userid['user_id'];

    // retrieving favorites 
    $query = "SELECT * FROM AF_Location NATURAL JOIN AF_Attraction NATURAL JOIN AF_Favorite WHERE user_id=:user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userid);
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}

// deletes attraction/user from Favorites
function deleteFavorite($attraction_id, $username)
{
    global $db;

    // getting userid for associated username 
    $query = "SELECT user_id FROM AF_User WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $userid = $statement->fetch();
    $userid = $userid['user_id'];

    // deleting from favorites
    $query = "DELETE FROM AF_Favorite WHERE user_id=:user_id AND attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':user_id', $userid);
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();
}

// returns bool value for whether attraction is currently favorited by the user or not
function isFavorited($attraction_id, $username)
{
    global $db;

    // getting userid for associated username 
    $query = "SELECT user_id FROM AF_User WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $userid = $statement->fetch();
    $userid = $userid['user_id'];

    // deleting from favorites
    $query = "SELECT * FROM AF_Favorite WHERE user_id=:user_id AND attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':user_id', $userid);
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $result = $statement->fetch();
    if ($result != NULL) {
        $result = True;
    } else {
        $result = False;
    }
    $statement->closeCursor();

    return $result;
}

// retrieves avg rating for given attraction 
function getAvgRating($attraction_id)
{
    global $db;
    // retrieving avg rating and number of ratings for the attraction 
    $query = "SELECT AVG(rating_value) AS rating, COUNT(rating_value) AS num FROM AF_Rating NATURAL JOIN AF_Attraction WHERE attraction_id=:attraction_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();
    return $result; 
}

// retrieves all ratings given by the user for a given attraction 
function getRatingForUserForAttraction($attraction_id, $username)
{
    global $db;
    // getting userid for associated username 
    $query = "SELECT user_id FROM AF_User WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $userid = $statement->fetch();
    $userid = $userid['user_id'];

    // retrieving all user's ratings for this attraction
    $query = "SELECT * FROM AF_Rating NATURAL JOIN AF_Attraction WHERE attraction_id=:attraction_id AND user_id=:user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->bindValue(':user_id', $userid);
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();
    return $result; 
}

// create or edit new rating by user for an attraction
function addOrEditRating($attraction_id, $username, $value)
{
    global $db;
    // getting userid for associated username 
    $query = "SELECT user_id FROM AF_User WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $userid = $statement->fetch();
    $userid = $userid['user_id'];

    // if user already has a rating, modify it 
    if (getRatingForUserForAttraction($attraction_id, $username) != NULL )  {
        $query = "UPDATE  AF_Rating SET rating_value=:rating_value WHERE user_id=:user_id and attraction_id=:attraction_id;";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $userid);
        $statement->bindValue(':attraction_id', $attraction_id);
        $statement->bindValue(':rating_value', $value);
        $statement->execute(); 
        $statement->closeCursor();
        
    } else {
    // else, insert new rating 
    // inserting rating
    $query = "INSERT INTO AF_Rating(user_id, attraction_id, rating_value) VALUES (:user_id, :attraction_id, :rating_value)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userid);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->bindValue(':rating_value', $value);
    $statement->execute(); 
    $statement->closeCursor();
    }
   
}

// retrieves all cities for city filtering
function getAllCities()
{
    global $db;
    $query = "SELECT DISTINCT city FROM AF_Location";
    $statement = $db->prepare($query);
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}

// retrieves all states for state filtering
function getAllStates()
{
    global $db;
    $query = "SELECT DISTINCT state FROM AF_Location";
    $statement = $db->prepare($query);
    $statement->execute(); 
    $result = $statement->fetchAll(); 
    $statement->closeCursor();
    return $result; 
}


?>
