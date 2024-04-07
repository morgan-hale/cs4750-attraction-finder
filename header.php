<!-- header after login page -->

<?php session_start(); ?>

<header>  
  <nav class="navbar navbar-expand-sm" style="background-color:#739AC7" >
    <div class="container-fluid">    
    <a class="nav-link" href="search_page.php" style="color:white"><img src="logo.png" width=100px height=75px /></a>
        
      
      <li class="nav-item">
        <a class="nav-link" href="search_page.php" style="color:white">Search</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="edit_page.php" style="color:white">Edit</a>
      </li>       
      <li class="nav-item">
        <a class="nav-link" href="./signup_login/signup.php"style="color:white">Log in</a>
      </li>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="color:white"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <!-- check if currently logged in, display Log out button 
               otherwise, display sign up and log in buttons -->
          <!-- <?php if (!isset($_SESSION['username'])) { ?>              
                         
          <?php  } else { ?>                    
            <li class="nav-item">                  
              <a class="nav-link" href="/signup_login/login.php" style="color:white">My Profile</a>
            </li>
          <?php } ?> -->
        
         
         
        </ul>
      </div>
    </div>
  </nav>
</header>    