<!-- Attraction Search Page, the main landing page after logging in.
note that non-logged-in users can access this, but they can't see edit or my profile  -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); 
require("attraction-finder-db.php");
?>

<?php 
  $_SESSION['attr_id'] = "";
  // getting list of all attractions and concated locations to display in big table
  $list_of_attractions_with_locations = getAllAttractionsWithLocations();
  $list_of_attraction_types = getAllAttractionTypes();


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
      $_POST['typeFilter'] = null;
    } 
    else if (!empty($_POST['typeFilter']))  
    {
      $list_of_attractions_with_locations = filterAttractionsByType($_POST['typeFilter']);
    } 
  }
?>

<!DOCTYPE html> 
<html> 
<head> 
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Attraction search/filter page for Attraction Finder">
  <meta name="keywords" content="CS 4750 Term Project, Attraction Finder: Search Page"> 
  <title>Attraction Finder Landing Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body>  
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Search for Attractions!</h2>
    </div>  
  </div>
  
 <div>
  <!-- attraction name search bar -->
  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Attraction Name:
            <input type='text' class='form-control' 
                   id='search_val' name='search_val' 
                   value="" /> 
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
      <div class="col-4 d-grid">
        <select id="typeFilter" name="typeFilter" onchange="this.form.submit()">
            <option disabled selected value="">Filter by Attraction Type</option>
            <?php foreach ($list_of_attraction_types as $type): ?>
                <option value="<?php echo $type["attraction_type_name"]; ?>" <?php echo isset($_POST['typeFilter']) && $_POST['typeFilter'] == $type["attraction_type_name"] ? 'selected' : ''; ?>>
                    <?php echo $type["attraction_type_name"]; ?>
                </option>
            <?php endforeach; ?>
        </select>
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
<table class="w3-table w3-bordered w3-card-4 center attraction-table" style="width:100%">
  <thead>
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="40%"><b>Address</b></th>  
    <?php if (!empty($_POST['typeFilter'])): ?> 
    <th width="15%"><b>Attraction Type</b></th>
    <?php endif ?>

    <th width="10%"><b>Details</b></th>
  </tr>
  </thead>
  <?php foreach ($list_of_attractions_with_locations as $attr_info): ?>
  <tr> 
     <td><?php echo $attr_info['attraction_name']; ?></td>        
     <td><?php echo $attr_info["CONCAT(street_address, ', ', city,', ', state,' ', zip_code)"]; ?></td>  
     <?php if (!empty($_POST['typeFilter'])): ?> 
     <td><?php echo $attr_info["attraction_type_name"]; ?></td> 
     <?php endif ?>
     <td>
      <a href="attraction_detail_page.php?id= <?php echo $attr_info['attraction_id'];?>"  class="btn btn-primary active" >
        View Details
      </a>
    </td>         
   </tr>
<?php endforeach; ?>  
</table>
</div>   


<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<!-- <script>
  document.getElementById('typeFilter').addEventListener('change', function() {
    var selectedType = this.value;
    // Send an AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Update the table body content with the filtered results
        document.querySelector('.attraction-table tbody').innerHTML = xhr.responseText;
      }
    };
    xhr.send('typeFilter=' + selectedType);
  });
</script> -->

</body>
</html>