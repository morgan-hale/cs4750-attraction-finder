<!-- This will be the page for logged-in users to edit attractions they created. Work in progress -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); // connects to database
require("attraction-finder-db.php");
?>

<?php 
$list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']);
// var_dump($list_of_attractions_with_locations);
?>


<!DOCTYPE html> <!-- this isn't technically a tag, just says "use HTML5" -->
<html> <!-- include whole document in this -->
<head> <!-- head gives extra info to browser -->
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">   <!-- this scales to device's width -->
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Attraction editting page for Attraction Finder">
  <!-- description is good to give more info about the page  -->
  <meta name="keywords" content="CS 4750 Term Project, Attraction Finder"> 
  <!-- keywords help with SEO stuff. need clear key words!  -->
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  <!-- icons also help with usability. this is the favicon. used with bookmarks -->
  <title>Attraction Finder Landing Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
  <!-- don't format your page from scratch! use existing bootstrap and only customize what needs to be customized. these 3 lines above help.  -->
</head>

<body>  <!-- contains actual content of the page. everything the user will see  -->

    <br/><br/>

<hr/>
<div class="container">
<h3>My Attractions</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
    <!-- these are the column names. bolded. -->
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="30%"><b>Address</b></th>  
  </tr>
  </thead>
  <!-- php code to iterate array of results, display each row in this table -->
  <!-- iterate array of results, display the existing requests -->
  <?php foreach ($list_of_attractions_with_locations as $attr_info): ?>
    <!-- for each ([collection of results] as [row_variable_name, whatever you want to call it. could call it row]) -->
  <tr> 
    <!-- tr is row. td is column -->
     <!-- echo is command in php to display text on screen. echo this column of this row -->
     <td><?php echo $attr_info['attraction_name']; ?></td>        
     <td><?php echo $attr_info["CONCAT(street_address, ', ', city,', ', state,' ', zip_code)"]; ?></td>            
     <td> 
      <!-- we are creating update and delete buttons for each individual row -->
       <form action="request.php" method="post">   <!-- get --> 
       <!-- form says grab everything in here. action specifies where to send it, request.php here. request is "post", "get", ...? but get displays in URL and has a character limit. POST request looks at form, grabs all inputs, packs into input, sends object over internet, and no one sees it and there's no size limit. so we'll use post here. -->
          <input type="submit" value="Update" name="updateBtn" 
                 class="btn btn-primary" /> 
                 <!-- name is what you'll use to refer to it in code. like id. class attribute is how you format with bootstrap. bootstrap has general btn (button) format, and other button formats like btn-priamry. check bootstrap website for more formats. -->
          <input type="hidden" name="reqId" 
                 value="<?php echo $req_info['reqId']; ?>" /> 
            <!-- here we are passing in the ID of the row we're updating. type hidden to hide it when passed. but PK as name. -->
       </form>
     </td>
     <td>
       <form action="request.php" method="post">   <!-- get --> 
          <input type="submit" value="Delete" name="deleteBtn" 
                 class="btn btn-danger" /> 
          <input type="hidden" name="reqId" value="<?php echo $req_info['reqId']; ?>"  />
                 <!-- make sure you use different colors for buttons to help the user -->
       </form>
     </td>
   </tr>
<?php endforeach; ?>  
</table>
</div>   


</body>
</html>