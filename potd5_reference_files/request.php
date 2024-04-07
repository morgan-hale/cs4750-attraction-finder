<!-- this is from potd5. use it as a reference to build display pages for our project. -->

<?php
require("connect-db.php"); // connects to database
require("request-db.php");  
?>

<?php // form handling
$list_of_requests = getAllRequests(); // using the SELECT * FROM requests function in request-db.php . putting it here ensures it is called every time page is loaded (not just when form is submitted?)
// var_dump($list_of_requests); // function for debugging. detects type, size of objective you pass in and returns the object so you can confirm it


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['addBtn']))  
  {
    // each of the POST names come from the name of the input in the form (scroll down and see)
    addRequests($_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
    $list_of_requests = getAllRequests(); // reloading the database table 

  } 
  else if (!empty($_POST['updateBtn'])) 
  {
    // we need to retrieve info for that particualr request 
    $request_to_update = getRequestById($_POST['reqId']);
    // var_dump($request_to_update); // checking that we have the row we want to change
  }
  else if (!empty($_POST['cofmBtn']))  
  {
    // each of the POST names come from the name of the input in the form (scroll down and see)
    var_dump($_POST['cofm_reqId'], $_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
    updateRequest($_POST['cofm_reqId'], $_POST['requestedDate'], $_POST['roomNo'], $_POST['requestedBy'], $_POST['requestDesc'], $_POST['priority_option']);
    $list_of_requests = getAllRequests(); // reloading the database table 
  } 
  else if (!empty($_POST['deleteBtn']))
   {
    deleteRequest($_POST['reqId']);
    $list_of_requests = getAllRequests(); // reloading the database table 

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
  <title>Maintenance Services</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
  <!-- don't format your page from scratch! use existing bootstrap and only customize what needs to be customized. these 3 lines above help.  -->
</head>

<body>  <!-- contains actual content of the page. everything the user will see  -->
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Maintenance Request PROJECT REPO VERSION</h2>
    </div>  
  </div>
  
  <!---------------->

  <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
  <!-- form tag specifies where user can interact. when user hits submit, what's in the form will be sent to server. -->

    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Requested date:
            <input type='text' class='form-control' 
                   id='requestedDate' name='requestedDate' 
                   placeholder='Format: yyyy-mm-dd' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}" 
                   value="<?php if ($request_to_update != null) echo $request_to_update['reqDate'] ?>" /> 
                   <!-- ^^if we are not updating, default value of form is empty. but if we are updating, fill with current row -->
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Room Number:
            <input type='text' class='form-control' id='roomNo' name='roomNo' 
              value="<?php if ($request_to_update != null) echo $request_to_update['roomNumber'] ?>" /> 
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested by: 
            <input type='text' class='form-control' id='requestedBy' name='requestedBy'
                   placeholder='Enter your name'
                   value="<?php if ($request_to_update != null) echo $request_to_update['reqBy'] ?>" /> 
          </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class="mb-3">
            Description of work/repair:
            <input type='text' class='form-control' id='requestDesc' name='requestDesc'
              value="<?php if ($request_to_update != null) echo $request_to_update['repairDesc'] ?>" /> 
        </div>
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <div class='mb-3'>
            Requested Priority:
            <select class='form-select' id='priority_option' name='priority_option'>
              <option selected></option>
              <option value='high' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='high') echo ' selected="selected"' ?> >
                High - Must be done within 24 hours</option>
              <option value='medium' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='medium') echo ' selected="selected"' ?> >
                Medium - Within a week</option>
              <option value='low' <?php if ($request_to_update!=null && $request_to_update['reqPriority']=='low') echo ' selected="selected"' ?> >
                Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid ">
      <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
           title="Submit a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid ">
      <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
           title="Update a maintenance request" />   
      <input type="hidden" value="<?php echo $_POST['reqId']; ?>" name="cofm_reqId" />        
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


<hr/>
<div class="container">
<h3>List of requests</h3>
<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
  <thead>
    <!-- these are the column names. bolded. -->
  <tr style="background-color:#B0B0B0"> 
    <th width="30%"><b>ReqID</b></th>
    <th width="30%"><b>Date</b></th>        
    <th width="30%"><b>Room#</b></th> 
    <th width="30%"><b>By</b></th>
    <th width="30%"><b>Description</b></th>        
    <th width="30%"><b>Priority</b></th> 
    <th><b>Update?</b></th>
    <th><b>Delete?</b></th>
  </tr>
  </thead>
  <!-- php code to iterate array of results, display each row in this table -->
  <!-- iterate array of results, display the existing requests -->
  <?php foreach ($list_of_requests as $req_info): ?>
    <!-- for each ([collection of results] as [row_variable_name, whatever you want to call it. could call it row]) -->
  <tr> 
    <!-- tr is row. td is column -->
     <td><?php echo $req_info['reqId']; ?></td>
     <!-- echo is command in php to display text on screen. echo this column of this row -->
     <td><?php echo $req_info['reqDate']; ?></td>        
     <td><?php echo $req_info['roomNumber']; ?></td>          
     <td><?php echo $req_info['reqBy']; ?></td>
     <td><?php echo $req_info['repairDesc']; ?></td>        
     <td><?php echo $req_info['reqPriority']; ?></td>               
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


<br/><br/>

<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>