<!-- detail page for attractoins, showing price and phone info. Still in progress  -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); // connects to database
require("attraction-finder-db.php");
?>

<?php // form handling
// still figuring out how to send ID over
  $attraction_info = getAttractionById($_POST['']);
  $price_info = getPricesforAttraction($_POST['']);
  $phone_info = getPhoneNumbersforAttraction($_POST['']);

  var_dump($attraction_info);
  var_dump($price_info);
  var_dump($phone_info);


 
?>

<!DOCTYPE html> <!-- this isn't technically a tag, just says "use HTML5" -->
<html> <!-- include whole document in this -->
<head> <!-- head gives extra info to browser -->
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">   <!-- this scales to device's width -->
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Attraction search/filter page for Attraction Finder">
  <!-- description is good to give more info about the page  -->
  <meta name="keywords" content="CS 4750 Term Project, Attraction Finder"> 
  <!-- keywords help with SEO stuff. need clear key words!  -->
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  <!-- icons also help with usability. this is the favicon. used with bookmarks -->
  <title>Attraction Detail Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
  <!-- don't format your page from scratch! use existing bootstrap and only customize what needs to be customized. these 3 lines above help.  -->
</head>

<body>  <!-- contains actual content of the page. everything the user will see  -->
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Attraction Detail Page</h2>
    </div>  
  </div>
  
  <!---------------->
 <div>

<br/><br/>

<hr/>
<div class="container">
<h3>Selected Attraction</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
    <!-- these are the column names. bolded. -->
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="30%"><b>Address</b></th>  
  </tr>
  </thead>
  <?php foreach ($attraction_info as $attr_info): ?>
  <tr> 
     <td><?php echo $attr_info['attraction_name']; ?></td>        
   </tr>
<?php endforeach; ?>  
</table>
</div> 

<div class="container">
<h3>Attraction Phone Numbers</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
    <!-- these are the column names. bolded. -->
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Label</b></th>        
    <th width="30%"><b>Phone Number</b></th>  
  </tr>
  </thead>
  <!-- php code to iterate array of results, display each row in this table -->
  <!-- iterate array of results, display the existing requests -->
  <?php foreach ($phone_info as $pho_info): ?>
    <!-- for each ([collection of results] as [row_variable_name, whatever you want to call it. could call it row]) -->
  <tr> 
    <!-- tr is row. td is column -->
     <!-- echo is command in php to display text on screen. echo this column of this row -->
     <td><?php echo $pho_info['label']; ?></td>        
     <td><?php echo $pho_info['phone']; ?></td>            
   </tr>
<?php endforeach; ?>  
</table>
</div>   

<div class="container">
<h3>Attraction Prices </h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
    <!-- these are the column names. bolded. -->
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Customer Type</b></th>        
    <th width="30%"><b>Price</b></th>  
  </tr>
  </thead>
  <!-- php code to iterate array of results, display each row in this table -->
  <!-- iterate array of results, display the existing requests -->
  <?php foreach ($price_info as $pri_info): ?>
    <!-- for each ([collection of results] as [row_variable_name, whatever you want to call it. could call it row]) -->
  <tr> 
    <!-- tr is row. td is column -->
     <!-- echo is command in php to display text on screen. echo this column of this row -->
     <td><?php echo $pri_info['customer_type']; ?></td>        
     <td><?php echo $pri_info['amount']; ?></td>            
   </tr>
<?php endforeach; ?>  
</table>
</div>   


<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>