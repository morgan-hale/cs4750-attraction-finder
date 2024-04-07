<!-- Attraction Search Page, the main landing page after logging in  -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); // connects to database
require("attraction-finder-db.php");
?>

<?php // form handling
  $list_of_attractions_with_locations = getAllAttractionsWithLocations();
  // var_dump($list_of_attractions_with_locations);

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if (!empty($_POST['searchBtn']))  
    {
      // each of the POST names come from the name of the input in the form (scroll down and see)
      $list_of_attractions_with_locations = searchAttractionByName($_POST['search_val']);
      // var_dump($list_of_attractions_with_locations);
    }  
    else if (!empty($_POST['refreshBtn']))  
    {
      $list_of_attractions_with_locations = getAllAttractionsWithLocations();
    } 
  }
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
  <title>Attraction Finder Landing Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
  <!-- don't format your page from scratch! use existing bootstrap and only customize what needs to be customized. these 3 lines above help.  -->
</head>

<body>  <!-- contains actual content of the page. everything the user will see  -->
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Attraction Finder Landing Page</h2>
    </div>  
  </div>
  
  <!---------------->
 <div>


  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
  <!-- form tag specifies where user can interact. when user hits submit, what's in the form will be sent to server. -->

    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Attraction Name:
            <input type='text' class='form-control' 
                   id='search_val' name='search_val' 
                   value="" /> 
                   <!-- ^^if we are not updating, default value of form is empty. but if we are updating, fill with current row -->
          </div>
        </td>
      </tr>
     
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Search" id="searchBtn" name="searchBtn" class="btn btn-dark"
           title="Submit name search" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Refresh List" id="refreshBtn" name="refreshBtn" class="btn btn-outline-dark"
           title="Return to entire list" />                  
      </div>	 
    </div>  
    <div>
  </div>  
  </form>

  </div>
</div>



<br/><br/>

<hr/>
<div class="container">
<h3>List of attractions</h3>
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