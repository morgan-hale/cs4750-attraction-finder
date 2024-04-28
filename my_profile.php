
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
    addAttraction($_POST['attr_name'], $_POST['address'], $_POST['city'], $_SESSION['username'], $_POST['state'], $_POST['zip_code'], $_POST['attr_type'], $_POST['attr_price']);
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
      <form id="deleteForm_<?php echo $attr_info['attraction_id']; ?>" action="edit_page.php" method="post">
        <input type="hidden" name="attrId" value="<?php echo $attr_info['attraction_id']; ?>" />
        <label for="deleteModal_<?php echo $attr_info['attraction_id']; ?>" class="btn btn-danger">Delete</label>
        <input type="checkbox" id="deleteModal_<?php echo $attr_info['attraction_id']; ?>" class="modal-checkbox" />
        <div id="deleteConfirmation_<?php echo $attr_info['attraction_id']; ?>" class="modal">
          <div class="modal-content">
            <p>Are you sure you want to delete this attraction?</p>
            <form action="edit_page.php" method="post">
              <input type="hidden" name="attrId" value="<?php echo $attr_info['attraction_id']; ?>" />
              <input type="submit" value="Yes" name="deleteBtn" class="btn btn-danger" />
              <label for="deleteModal_<?php echo $attr_info['attraction_id']; ?>" class="btn btn-secondary">No</label>
            </form>
          </div>
        </div>
      </form>
     </td>
   </tr>
<?php endforeach; ?>  
</table>
</div>   


<div id="confirmationModal" class="modal" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
        <button type="button" class="btn-close" onclick="hideConfirmationModal();" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this attraction?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideConfirmationModal();">Cancel</button>
        <button type="button" class="btn btn-danger" id="deleteBtn" name="deleteBtn" onclick="deleteAttraction();">Confirm</button>
      </div>
    </div>
  </div>
</div>

<style>
  /* CSS for modal */
.modal-checkbox {
  display: none;
}

.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.modal-checkbox:checked + .modal {
  display: block;
}
</style>

</body>
</html>