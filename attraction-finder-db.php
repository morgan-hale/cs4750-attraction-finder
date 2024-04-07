<!-- Functions interacting with the database -->

<?php

function getAllAttractionsWithLocations()
{
    global $db; // don't keep making new database instance. keep using this global variable! 

    $query = "SELECT attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location;";
    $statement = $db->prepare($query); // just compiles. we don't need to pass in values so just execute! 
    $statement->execute(); 
    $result = $statement->fetchAll(); // fetches all rows in result. just fetch() returns first row. we need to save it to a variable, we'll call it result
    $statement->closeCursor();
    // we need to return the result back to the form 
    return $result; // form will iterate over results and display one row at a time

}


function searchAttractionByName($search_value)
{
    global $db; 
    $query = "SELECT attraction_name, CONCAT(street_address, ', ', city,', ', state,' ', zip_code) FROM AF_Attraction NATURAL JOIN AF_Location WHERE attraction_name LIKE :search_val;";
    try{
        $statement = $db->prepare($query);
        $concatenatedstring = "%" . $search_value . "%";
        // var_dump($concatenatedstring);
        $statement->bindValue(':search_val', $concatenatedstring);
        $statement->execute(); 
        $result = $statement->fetchAll(); // fetches all rows in result. just fetch() returns first row. we need to save it to a variable, we'll call it result
        // var_dump($result);
        $statement->closeCursor();
        // we need to return the result back to the form 
        return $result; // form will iterate over results and display one row at a time
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }

}

function getAllAttractions()
{
    global $db; // don't keep making new database instance. keep using this global variable! 

    $query = "SELECT * FROM AF_Attraction NATURAL JOIN AF_Location";
    $statement = $db->prepare($query); // just compiles. we don't need to pass in values so just execute! 
    $statement->execute(); 
    $result = $statement->fetchAll(); // fetches all rows in result. just fetch() returns first row. we need to save it to a variable, we'll call it result
    $statement->closeCursor();

    // we need to return the result back to the form 
    return $result; // form will iterate over results and display one row at a time

}

function addAttraction($attraction_name, $street_address, $city, $creator_id)
{
    global $db;  // this is same as global database saved in connect-db file 

    // $query = "INSERT INTO requests(reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES ('2024-03-18', 'ABC', 'Someone', 'fix light', 'low')";
    // bc PK auto increments, don't need to include it here

    $query = "INSERT INTO AF_Attraction(attraction_name, street_address, city, creator_id) VALUES (:attraction_name, :street_address, :city, :creator_id)";
    // ^ this is a PREPARED STATEMENT and is much better security bc input must follow a tempalte 

    try{
        $statement = $db->prepare($query);

        // fill in the value 
        $statement->bindValue(':attraction_name', $attraction_name);
        $statement->bindValue(':street_address', $street_address);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':creator_id', $creator_id);

        // execute 
        $statement->execute(); // if you don't call execute then it won't run anything
        $statement->closeCursor(); // release the Cursor you you don't keep using the instance over and over?     
    } catch (PDOException $e)
    {
        $e->getMessage();
    } catch (Exception $e)
    {
        $e->getMessage();
    }

   
}


function getAttractionById($id)  
{
    global $db;
    $query = "SELECT * FROM AF_Attraction WHERE attraction_id=:attraction_id"; // using the prepared statement template name
    $statement = $db->prepare($query); 
    // remember a prepared statement let's us precompile. but the colons still mean a "fill in the blank" value so we still need to fill that in here
    $statement->bindValue(':attraction_id', $id); //actually filling in the value here
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();

    return $result;


}

function getPricesforAttraction($id)
{
    global $db;
    $query = "SELECT * FROM AF_CustomerPrice WHERE attraction_id=:attraction_id";
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();
    return $result; 
}

function getPhoneNumbersforAttraction($id)
{
    global $db;
    $query = "SELECT * FROM AF_AttractionPhone WHERE attraction_id=:attraction_id";
    $statement = $db->prepare($query); 
    $statement->bindValue(':attraction_id', $id); 
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();
    return $result; 
}


function updateAttraction($attraction_id, $attraction_name, $street_address, $city, $creator_id)
{
    global $db;
    $query = "UPDATE AF_Attraction SET attraction_name=:attraction_name, street_address=:street_address, city=:city, creator_id=:creator_id WHERE attraction_id=:attraction_id" ; 
 
    $statement = $db->prepare($query);
    $statement->bindValue(':attraction_id', $attraction_id);
    $statement->bindValue(':attraction_name', $attraction_name);
    $statement->bindValue(':roomstreet_addressNumber', $street_address);
    $statement->bindValue(':city',$city);
    $statement->bindValue(':creator_id', $creator_id);
 
    $statement->execute();
    $statement->closeCursor();

}

function deleteAttraction($attraction_id)
{

    global $db;
    $query = "DELETE FROM AF_Attraction WHERE attraction_id=:attraction_id"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':attraction_id', $attraction_id);
    $statement->execute(); 
    $statement->closeCursor();

    
}

?>
