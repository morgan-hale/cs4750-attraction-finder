<!-- Functions interacting with the database -->

<?php

// gets attraction with concated locations for search page
function getAllAttractionsWithLocations()
{
    global $db; // don't keep making new database instance. keep using this global variable! 

    $query = "SELECT attraction_id, attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code), attraction_type_name FROM AF_Attraction a NATURAL JOIN AF_Location l NATURAL JOIN AF_Attraction_Has_Type ht NATURAL JOIN AF_AttractionType t WHERE a.attraction_id = ht.attraction_id AND ht.attraction_type_id = t.attraction_type_id;";
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

// returns search result after user searches by attraction name via search bar (form)
function searchAttractionByName($search_value)
{
    global $db; 
    $query = "SELECT attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code), attraction_type_name FROM AF_Attraction a NATURAL JOIN AF_Location l NATURAL JOIN AF_Attraction_Has_Type ht NATURAL JOIN AF_AttractionType t WHERE a.attraction_id = ht.attraction_id AND ht.attraction_type_id = t.attraction_type_id AND attraction_name LIKE :search_val;";
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
function addAttraction($attraction_name, $street_address, $city, $username, $state, $zip_code, $attraction_type, $attraction_price)
{
    global $db;  

     // adding to location table (if needed) before attraction table 
     $type_id = $attraction_type[4];
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

    // now insterting into attraction 
    $query = "INSERT INTO AF_Attraction(attraction_name, street_address, city, creator_id) VALUES (:attraction_name, :street_address, :city, :creator_id)";
    try{
        $statement = $db->prepare($query);
        $statement->bindValue(':attraction_name', $attraction_name);
        $statement->bindValue(':street_address', $street_address);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':creator_id', $userid);
        $statement->execute(); 
        $statement->closeCursor(); // release the Cursor you you don't keep using the instance over and over?     
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
    $query = "INSERT INTO AF_CustomerPrice(attraction_id, customer_type, amount) VALUES (:attraction_id, 'Adult',:attraction_price)";
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_price', $attraction_price);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->execute();
}


// return all attraction information (base table, location, price, phone, typeIDs, type names) for a given attraction ID
function getAttractionById($id)  
{
    global $db;
    $query = "SELECT * FROM AF_Attraction NATURAL JOIN AF_Location NATURAL JOIN AF_CustomerPrice NATURAL JOIN AF_Attraction_Has_Type NATURAL JOIN AF_AttractionType WHERE attraction_id=:attraction_id"; // using the prepared statement template name
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetch();  //!! Note difference btw fetch (returns 1 row) and fetchAll (returns all rows)!
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
    $result = $statement->execute();

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
// !! NOTE: no deletion confirmation is implemented yet so be careful
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
?>
