<!-- this is a copy of request-db.php from the potd. 
in the process of converting the functions to interface with our project's tables instead -->

<?php

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

function getRequestById($id)  
{
    global $db;
    $query = "SELECT * FROM requests WHERE reqId=:reqId"; // using the prepared statement template name
    $statement = $db->prepare($query); 
    // remember a prepared statement let's us precompile. but the colons still mean a "fill in the blank" value so we still need to fill that in here
    $statement->bindValue(':reqId', $id); //actually filling in the value here
    $statement->execute(); 
    $result = $statement->fetch(); 
    $statement->closeCursor();

    return $result;


}

function updateRequest($reqId, $reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
    global $db;
    $query = "UPDATE requests SET reqDate=:reqDate, roomNumber=:roomNumber, reqBy=:reqBy, repairDesc=:repairDesc, reqPriority=:reqPriority WHERE reqId=:reqId" ; 
 
    $statement = $db->prepare($query);
    $statement->bindValue(':reqId', $reqId);
    $statement->bindValue(':reqDate', $reqDate);
    $statement->bindValue(':roomNumber', $roomNumber);
    $statement->bindValue(':reqBy',$reqBy);
    $statement->bindValue(':repairDesc', $repairDesc);
    $statement->bindValue(':reqPriority', $reqPriority);
 
    $statement->execute();
    $statement->closeCursor();

}

function deleteRequest($reqId)
{

    global $db;
    $query = "DELETE FROM requests WHERE reqId=:reqId"; 
    $statement = $db->prepare($query); 
    $statement-> bindValue(':reqId', $reqId);
    $statement->execute(); 
    $statement->closeCursor();

    
}

?>
