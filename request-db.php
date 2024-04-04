<!-- this is from potd5. use it as a reference to interface with tables for our project. -->


<?php
function addRequests($reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority)
{
    global $db;  // this is same as global database saved in connect-db file 


    $reqDate = date('Y-m-d'); // ensure proper data type before inserting it into a db 

    // $query = "INSERT INTO requests(reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES ('2024-03-18', 'ABC', 'Someone', 'fix light', 'low')";
    // bc PK auto increments, don't need to include it here

    $query = "INSERT INTO requests(reqDate, roomNumber, reqBy, repairDesc, reqPriority) VALUES (:reqDate, :roomNumber, :reqBy, :repairDesc, :reqPriority)";
    // ^ this is a PREPARED STATEMENT and is much better security bc input must follow a tempalte 

    try{
        // $statement = $db ->query($query); // this query function compiles and executes your query right away. updates to table in database

        // when he have dynmaic input, we need prepared statement --> pre-compile the query --> fill in value --> execute. see below:
        // prepared statement. precompiles 
        $statement = $db->prepare($query);

        // fill in the value 
        $statement->bindValue(':reqDate', $reqDate);
        $statement->bindValue(':roomNumber', $roomNumber);
        $statement->bindValue(':reqBy', $reqBy);
        $statement->bindValue(':repairDesc', $repairDesc);
        $statement->bindValue(':reqPriority', $reqPriority);

        var_dump($statement);

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

function getAllRequests()
{
    global $db; // don't keep making new database instance. keep using this global variable! 

    $query = "SELECT * FROM requests";
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
