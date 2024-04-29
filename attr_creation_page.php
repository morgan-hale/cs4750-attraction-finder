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

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
 if (!empty($_POST['addBtn']))  
  {
    addAttraction($_POST['attr_name'], $_POST['address'], $_POST['city'], $_SESSION['username'], $_POST['state'], $_POST['zip_code'], $_POST['attr_type'], $_POST['attr_type2'], $_POST['phone_label'], $_POST['phone_number'], $_POST['phone_label2'], $_POST['phone_number2'], $_POST['cust_type'], $_POST['attr_price'],$_POST['cust_type2'], $_POST['attr_price2']);
    $list_of_attractions_with_locations = getAllAttractionsWithLocationsByCreator($_SESSION['username']); //refreshing table view
    
    echo "Success!\n";
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
<a href="edit_page.php"  class="btn btn-primary active" > Back to Edit Page </a>

<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
  <!-- form tag specifies where user can interact. when user hits submit, what's in the form will be sent to server. -->
    <h3> Create New Attraction</h3>
    <table style="width:98%">
    <tr>
        <td colspan=2>
          <div class='mb-3'>
            Attraction Name:
            <input type='text' class='form-control' id='attr_name' name='attr_name'
                   placeholder='Enter a name for the attraction'
                    required /> 
          </div>
        </td>
      </tr>
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Street Address:
            <input type='text' class='form-control' 
                   id='address' name='address' 
                    required /> 
                   <!-- ^^if we are not updating, default value of form is empty. but if we are updating, fill with current row -->
          </div>
        </td>
        <td>
          <div class='mb-3'>
            City:
            <input type='text' class='form-control' id='city' name='city' 
               required /> 
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
            State:
            <input type='text' class='form-control' 
                   id='state' name='state' 
                    required /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Zip Code:
            <input type='text' class='form-control' id='zip_code' name='zip_code' 
              required /> 
          </div>
        </td>
      </tr>
    <tr>
        <td width="50%">
          <div class='mb-3'>
            Select Type of Attraction:
            <select class="form-control" id="attr_type" name="attr_type">
                    <option value="type1">Hike</option>
                    <option value="type2">Restaurant</option>
                    <option value="type3">Theme Park</option>
                    <option value="type4">Outdoor Activity</option>
                    <option value="type5">Indoor Activity</option>
                </select>
          </div>
        </td>
        <td>
          <div class='mb-3'>
          Select a Secondary Type of Attraction (if desired):
                <select class="form-control" id="attr_type2" name="attr_type2">
                    <option value="type1"></option>
                    <option value="type1">Hike</option>
                    <option value="type2">Restaurant</option>
                    <option value="type3">Theme Park</option>
                    <option value="type4">Outdoor Activity</option>
                    <option value="type5">Indoor Activity</option>
                </select>
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
          Phone Number Label (if desired):
            <input type='text' class='form-control' id='phone_label' name='phone_label'
                   placeholder='Enter a label for the following phone number, like Help Desk' 
                     /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
          Enter a phone number associated with the label:
            <input type='text' class='form-control' id='phone_number' name='phone_number'
                   placeholder='Enter a phone number in the pattern 123-456-7890' pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" 
                   title="Please enter a valid phone number (e.g., xxx-xxx-xxxx)" /> 
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
          Second Phone Number Label (if desired):
            <input type='text' class='form-control' id='phone_label2' name='phone_label2'
                   placeholder='Enter a label for the following phone number, like Help Desk' 
                     /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
          Enter a phone number associated with the label:
            <input type='text' class='form-control' id='phone_number2' name='phone_number2'
                   placeholder='Enter a phone number in the pattern 123-456-7890' pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" 
                   title="Please enter a valid phone number (e.g., xxx-xxx-xxxx)" /> 
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
          Customer Type:
            <input type='text' class='form-control' id='cust_type' name='cust_type'
                   placeholder='Enter a name describing the customer, like Under 12' 
                    required /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
          Enter an associated price for this customer type:
            <input type='text' class='form-control' id='attr_price' name='attr_price'
                   placeholder='Enter a dollar amount, or 0.00 if free' pattern="\d+(\.\d{2})?" 
                   title="Please enter a valid dollar amount (e.g., 100.00), even if it's free (0.00)" required /> 
          </div>
        </td>
      </tr>

      <tr>
        <td width="50%">
          <div class='mb-3'>
          Second Customer Type (if desired):
            <input type='text' class='form-control' id='cust_type2' name='cust_type2'
                   placeholder='Enter a name describing the customer, like Under 12' 
                     /> 
          </div>
        </td>
        <td>
          <div class='mb-3'>
          Enter an associated price for this customer type:
            <input type='text' class='form-control' id='attr_price2' name='attr_price2'
                   placeholder='Enter a dollar amount, or 0.00 if free' pattern="\d+(\.\d{2})?" 
                   title="Please enter a valid dollar amount (e.g., 100.00)"  /> 
          </div>
        </td>
      </tr>

     

    </table>

    <div class="row g-3 mx-auto">  
  
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Create new attraction" />                  
      </div>	    

      <div class="col-4 d-grid">
        <input type="reset" value="Clear All Inputs" name="resetBtn" id="resetBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  
  </form>

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