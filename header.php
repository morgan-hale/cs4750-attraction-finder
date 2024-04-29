<!-- header after login page -->

<?php session_start(); ?>

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
<header>  
  <nav class="navbar navbar-expand-sm" style="background-color:#739AC7" >
    <div class="container-fluid">    
    <a class="nav-item nav-link" href="search_page.php" style="color:white"><img src="logo.png" width=100px height=75px /></a>
    <a class="nav-item nav-link" href="search_page.php" style="color:white">Search</a>
    <?php if (isset($_SESSION['username'])) : ?>    
      <a class="nav-item nav-link" href="edit_page.php" style="color:white">Edit</a>
    <?php endif ?>          
    <?php if (!isset($_SESSION['username'])) : ?>    
      <a class="nav-item nav-link btn btn-primary" href="./signup_login/login.php"style="color:white">Log in</a>
    <?php endif ?>          
    <?php if (isset($_SESSION['username'])) : ?>    
      <a class="nav-item nav-link" href="my_profile.php" style="color:white">My Profile - Welcome, <?php echo $_SESSION['username']; ?>! </a>
    <?php endif ?>    
         
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="color:white"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <!-- check if currently logged in, display Log out button 
               otherwise, display sign up and log in buttons -->
          <?php if (isset($_SESSION['username'])) : ?>              
            <!-- <li class="nav-item">    -->
            <a class="nav-item nav-link btn btn-primary" href="signup_login/login.php" style="color:white">Logout</a>
            <!-- </li>      -->
            <?php endif ?>          

        </ul>
      </div>
    </div>
  </nav>
</header>   
<br> 