<!-- this is a copy of the request.php file from potd5.
it will be the main landing page for our site. we need to convert it to 
display data for our project.  -->

<?php include('header.php'); ?>

<?php
require("connect-db.php"); // connects to database
require("attraction-finder-db.php");
?>

<?php // form handling
$list_of_attractions = getAllAttractions();
// var_dump($list_of_attractions);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['addBtn']))  
  {
    // each of the POST names come from the name of the input in the form (scroll down and see)
    addAttraction($_POST['attr_name'], $_POST['attr_street'], $_POST['attr_city'], $_POST['attr_creator_id']);
    $list_of_attractions = getAllAttractions(); // reloading the database table 

  } 
  else if (!empty($_POST['updateBtn'])) 
  {
    // we need to retrieve info for that particualr request 
    $attraction_to_update = getAttractionById($_POST['attr_id']);
    // var_dump($request_to_update); // checking that we have the row we want to change
  }
  else if (!empty($_POST['cofmBtn']))  
  {
    // each of the POST names come from the name of the input in the form (scroll down and see)
    var_dump($_POST['cofm_attrId'], $_POST['attr_name'], $_POST['attr_street'], $_POST['attr_city'], $_POST['attr_creator_id']);
    updateAttraction($_POST['cofm_attrId'], $_POST['attr_name'], $_POST['attr_street'], $_POST['attr_city'], $_POST['attr_creator_id']);
    $list_of_attractions = getAllAttractions(); // reloading the database table 
  } 
  else if (!empty($_POST['deleteBtn']))
   {
    deleteAttraction($_POST['attr_id']);
    $list_of_attractions = getAllAttractions(); // reloading the database table 
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
  <meta name="description" content="Maintenance request form, a small/toy web app for ISP homework assignment, used by CS 3250 (Software Testing)">
  <!-- description is good to give more info about the page  -->
  <meta name="keywords" content="CS 3250, Upsorn, Praphamontripong, Software Testing"> 
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

  

  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
  <!-- form tag specifies where user can interact. when user hits submit, what's in the form will be sent to server. -->

    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Attraction Name:
            <input type='text' class='form-control' 
                   id='attr_name' name='attr_name' 
                   value="<?php if ($request_to_update != null) echo $request_to_update['attraction_name'] ?>" /> 
                   <!-- ^^if we are not updating, default value of form is empty. but if we are updating, fill with current row -->
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Street Address:
            <input type='text' class='form-control' id='attr_street' name='attr_street' 
              value="<?php if ($request_to_update != null) echo $request_to_update['street_address'] ?>" /> 
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            City:
            <input type='text' class='form-control' id='attr_city' name='attr_city'
                   value="<?php if ($request_to_update != null) echo $request_to_update['city'] ?>" /> 
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Creator ID:
            <input type='text' class='form-control' id='attr_creator_id' name='attr_creator_id'
              value="<?php if ($request_to_update != null) echo $request_to_update['creator_id'] ?>" /> 
        </div>
        </td>
      </tr>
     
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Create new attraction" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update an attraction" />   
      <input type="hidden" value="<?php echo $_POST['attraction_id']; ?>" name="cofm_attrId" />        
             <!-- why do we need to attach this cofm_reqID? because of HTTP stateless property, the current reqId is only avaialbe ot this request. we need to carry it to the next round of form submission by passing a token to the next request -->
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
    <div>
  </div>  
  </form>

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
    <th width="30%"><b>attraction ID</b></th>
    <th width="30%"><b>Attraction Name</b></th>        
    <th width="30%"><b>Street Address</b></th> 
    <th width="30%"><b>City</b></th>
    <th width="30%"><b>Creator ID</b></th>        
    <th><b>Update?</b></th>
    <th><b>Delete?</b></th>
  </tr>
  </thead>
  <!-- php code to iterate array of results, display each row in this table -->
  <!-- iterate array of results, display the existing requests -->
  <?php foreach ($list_of_attractions as $attr_info): ?>
    <!-- for each ([collection of results] as [row_variable_name, whatever you want to call it. could call it row]) -->
  <tr> 
    <!-- tr is row. td is column -->
     <td><?php echo $attr_info['attraction_id']; ?></td>
     <!-- echo is command in php to display text on screen. echo this column of this row -->
     <td><?php echo $attr_info['attraction_name']; ?></td>        
     <td><?php echo $attr_info['street_address']; ?></td>          
     <td><?php echo $attr_info['city']; ?></td>
     <td><?php echo $attr_info['creator_id']; ?></td>        
     <td> 
      <!-- we are creating update and delete buttons for each individual row -->
       <form action="landing_page.php" method="post">   <!-- get --> 
       <!-- form says grab everything in here. action specifies where to send it, request.php here. request is "post", "get", ...? but get displays in URL and has a character limit. POST request looks at form, grabs all inputs, packs into input, sends object over internet, and no one sees it and there's no size limit. so we'll use post here. -->
          <input type="submit" value="Update" name="updateBtn" 
                 class="btn btn-primary" /> 
                 <!-- name is what you'll use to refer to it in code. like id. class attribute is how you format with bootstrap. bootstrap has general btn (button) format, and other button formats like btn-priamry. check bootstrap website for more formats. -->
          <input type="hidden" name="attr_id" 
                 value="<?php echo $attr_info['attraction_id']; ?>" /> 
            <!-- here we are passing in the ID of the row we're updating. type hidden to hide it when passed. but PK as name. -->
       </form>
     </td>
     <td>
       <form action="landing_page.php" method="post">   <!-- get --> 
          <input type="submit" value="Delete" name="deleteBtn" 
                 class="btn btn-danger" /> 
          <input type="hidden" name="reqId" value="<?php echo $attr_info['attraction_id']; ?>"  />
                 <!-- make sure you use different colors for buttons to help the user -->
       </form>
     </td>
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