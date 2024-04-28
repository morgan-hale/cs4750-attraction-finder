<!-- detail page for attractoins, showing price and phone info.  -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); // connects to database
require("attraction-finder-db.php");
?>

<?php // form handling
  $attr_id = $_GET['id']; // attractionID sent over from search page 
  $attr_id = trim($attr_id); // ID sent over a a string with leading blank space so this trims that off
  $attraction_info = getAttractionById($attr_id); // general attraction info 
  $price_info = getPricesforAttraction($attr_id); // price info 
  $phone_info = getPhoneNumbersforAttraction($attr_id); // phone number info
?>

<!DOCTYPE html> 
<html>  
<head> 
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Attraction search/filter page for Attraction Finder">
  <meta name="keywords" content="CS 4750 Term Project, Attraction Finder: Attraction Detail Page"> 
  <title>Attraction Detail Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>
<body> 
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Attraction Details</h2>
    </div>  
  </div>
<div>
  
<br/><br/>

<hr/>
<div class="container">
<h3>Selected Attraction</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="30%"><b>Street Address</b></th>  
    <th width="30%"><b>City</b></th>  
    <th width="30%"><b>State</b></th>  
    <th width="30%"><b>Zip Code</b></th>  
  </tr>
  </thead>
  <tr> 
     <td><?php echo $attraction_info['attraction_name']; ?></td>  
     <td><?php echo $attraction_info['street_address']; ?></td>  
     <td><?php echo $attraction_info['city']; ?></td>       
     <td><?php echo $attraction_info['state']; ?></td>       
     <td><?php echo $attraction_info['zip_code']; ?></td>       

</table>
</div> 

<hr/>
<div class="container">
  <h3>Attraction Phone Numbers</h3>
    <div class="row justify-content-center">  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
          <tr style="background-color:#B0B0B0"> 
            <th width="30%"><b>Label</b></th>        
            <th width="30%"><b>Phone Number</b></th>  
          </tr>
        </thead>
        <?php foreach ($phone_info as $pho_info): ?>
              <tr> 
                <td><?php echo $pho_info['label']; ?></td>        
                <td><?php echo $pho_info['phone']; ?></td>            
              </tr>
            <?php  endforeach; ?>  
      </table>
    </div>  
</div> 
<hr/>

<div class="container">
  <h3>Attraction Prices </h3>
  <div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
      <thead>
      <tr style="background-color:#B0B0B0"> 
        <th width="30%"><b>Customer Type</b></th>        
        <th width="30%"><b>Price</b></th>  
      </tr>
      </thead>
        <?php foreach ($price_info as $pri_info): ?>
          <tr> 
            <td><?php echo $pri_info['customer_type']; ?></td>        
            <td><?php echo $pri_info['amount']; ?></td>            
          </tr>
        <?php endforeach; ?>  
    </table>
  </div>   
</div>

<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>