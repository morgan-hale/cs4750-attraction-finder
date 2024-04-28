<!-- This will be the page for logged-in users to view and edit attractions they created.
// TODO: work on progress: so far only Attraction and Location information in editable. still need price, phone number, type (these are dynamic/unlimited bounds so more complicated)
-->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); 
require("attraction-finder-db.php");
?>

<?php 
// getting list of all attractions made by the logged in user
$list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']);
// var_dump($list_of_attractions_with_locations); // printing result to test
$attr_to_update = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (!empty($_POST['updateBtn'])) 
  {
    // if update btn is clicked, grab that attraction's info
    $attr_to_update = getAttractionById($_POST['attrId']);
  }
  // TODO: attraction creation still not working for some reason (even though updating through same table does work)
  else if (!empty($_POST['addBtn']))  
  {
    addAttraction($_POST['attr_name'], $_POST['address'], $_POST['city'], $_SESSION['username'], $_POST['state'], $_POST['zip_code']);
    $list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']); //refreshing table view
  } 
  else if (!empty($_POST['deleteBtn']))
   {
    deleteAttraction($_POST['attrId']);
    $list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']); //refreshing table view

  }  else if (!empty($_POST['cofmBtn']))  
  {
    // each of the POST names come from the name of the input in the form (scroll down and see)
    updateAttraction($_POST['cofm_attraction_id'], $_POST['attr_name'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip_code']);
    $list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']); //refreshing again
  } 
}
?>

<!DOCTYPE html> 
<html>
<head> 
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">   <!-- this scales to device's width -->
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Attraction editting page for Attraction Finder">
  <meta name="keywords" content="CS 4750 Term Project, Attraction Finder: Edit Paage"> 
  <title>Attraction Finder Edit Page</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body> 
<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
  <!-- form tag specifies where user can interact. when user hits submit, what's in the form will be sent to server. -->
    <h3> Create or update an attraction</h3>
    <table style="width:98%">
    <tr>
        <td colspan=2>
          <div class='mb-3'>
            Attraction Name:
            <input type='text' class='form-control' id='attr_name' name='attr_name'
                   placeholder='Enter a name for the attraction'
                   value="<?php if ($attr_to_update != null) echo $attr_to_update['attraction_name'] ?>" /> 
          </div>
        </td>
      </tr>
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Street Address:
            <input type='text' class='form-control' 
                   id='address' name='address' 
                   value="<?php if ($attr_to_update != null) echo $attr_to_update['street_address'] ?>" /> 
                   <!-- ^^if we are not updating, default value of form is empty. but if we are updating, fill with current row -->
          </div>
        </td>
        <td>
          <div class='mb-3'>
            City:
            <input type='text' class='form-control' id='city' name='city' 
              value="<?php if ($attr_to_update != null) echo $attr_to_update['city'] ?>" /> 
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
            State:
            <input type='text' class='form-control' 
                   id='state' name='state' 
                   value="<?php if ($attr_to_update != null) echo $attr_to_update['state'] ?>" /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Zip Code:
            <input type='text' class='form-control' id='zip_code' name='zip_code' 
              value="<?php if ($attr_to_update != null) echo $attr_to_update['zip_code'] ?>" /> 
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">  
    <?php if ($attr_to_update == null) : ?>
  
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Create new attraction" />                  
      </div>	    
      <?php endif ?>
      <?php if ($attr_to_update != null) : ?>
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update attraction" />   
      <input type="hidden" value="<?php echo $_POST['attraction_id']; ?>" name="cofm_attraction_id" />        
             <!-- why do we need to attach this cofm_reqID? because of HTTP stateless property, the current attrId is only available at this request. we need to carry it to the next round of form submission by passing a token to the next request -->
      </div>	    
      <?php endif ?>
      <div class="col-4 d-grid">
        <input type="reset" value="Reset Changes" name="resetBtn" id="resetBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  
  </form>

</div>


    <br/><br/>

<hr/>
<div class="container">
<h3>My Attractions</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="30%"><b>Address</b></th>  
  </tr>
  </thead>
  <?php foreach ($list_of_attractions_with_locations as $attr_info): ?>
  <tr> 
     <td><?php echo $attr_info['attraction_name']; ?></td>        
     <td><?php echo $attr_info["CONCAT(street_address, ', ', city,', ', state,' ', zip_code)"]; ?></td>            
     <td> 
      <!-- we are creating update and delete buttons for each individual row -->
       <form action="edit_page.php" method="post">   
          <input type="submit" value="Update" name="updateBtn" 
                 class="btn btn-primary" /> 
          <input type="hidden" name="attrId" 
                 value="<?php echo $attr_info['attraction_id']; ?>" /> 
       </form>
     </td>
     <td>
       <form action="edit_page.php" method="post">   
          <input type="submit" value="Delete" name="deleteBtn" 
                 class="btn btn-danger" /> 
          <input type="hidden" name="attrId" value="<?php echo $attr_info['attraction_id']; ?>"  />
       </form>
     </td>
   </tr>
<?php endforeach; ?>  
</table>
</div>   


</body>
</html>