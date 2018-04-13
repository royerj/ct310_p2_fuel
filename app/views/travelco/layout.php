<!DOCTYPE html>
<html lang='en-US'>

<head>
    <title>Travel Co</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='keywords' content='travel'>
    <meta name='author' content='Isaac Hall, Jacob Royer'>
    <?php echo Asset::css('bootstrap.css');?>
    <?php echo Asset::css('travelco.css');?>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>

<body>
  <!-- header -->
  <header>
    <?php
      function checkIfActive($requestUrl) {
        $current = basename($_SERVER['REQUEST_URI'], ".php");
        if ($current == $requestUrl)
          echo 'class="active"';
      }
    ?>
    <nav class="navbar navbar-inverse dark-opac-bg">
      <div class="container-fluid">
          <ul class="nav navbar-nav">
            <li <?=checkIfActive("index"); ?> ><a href="<?=Uri::Create("index.php/travelco/index"); ?>">Home</a></li>
            <li <?=checkIfActive("attractions"); ?> ><a href="<?=Uri::Create("index.php/travelco/attractions"); ?>">Attractions</a></li>
            <?php
              if(Auth::check()) {
                if(Auth::get('group') === '10') {
                  echo "<li ";
                  echo checkIfActive("add_attraction");
                  echo "><a href=" . Uri::create("index.php/travelco/add_attraction/") . ">+ Add New Attraction</a></li>";
                }
              }
            ?>
            <li <?=checkIfActive("about"); ?> ><a href="<?=Uri::Create("index.php/travelco/about"); ?>">About Us</a></li>
            <?php
              if(Auth::check()) {
                  echo "<li ";
                  echo checkIfActive("account");
                  echo "><a href=" . Uri::create("index.php/travelco/account/") . ">My Account</a></li>";
                  echo "<li><a href=" . Uri::create("index.php/travelco/logout/") . ">Logout</a></li>";
              } else {
                  echo "<li ";
                  echo checkIfActive("login");
                  echo "><a href=" . Uri::create("index.php/travelco/login/") . ">Login</a></li>";
              }
            ?>
          </ul>
      </div>
  </header>

  <!-- content view  -->
  <div id="content">
      <?=$content; ?>
  </div>

  <!-- footer -->
  <footer class="footer dark-opac-bg">
      <div class="container-fluid">
        <center>
          <?php
            $usr = Auth::get('username');
            if ($usr != 'guest') {
              $time = Auth::get('updated_at');
              echo "Logged in as: ";
              echo $usr;
              echo " | Logged in since: " . date('D M j h:i:s a',$time);
            } else {
              echo "Not logged in.";
            }
          ?>
          <p>
            Authors: Isaac Hall and Jacob Royer | Contact: <a href="mailto:isaac.hall@colostate.edu">isaac.hall@colostate.edu</a> or <a href="mailto:jacob.royer@colostate.edu">jacob.royer@rams.colostate.edu</a>
            <br/>This site is part of a CSU <a href='https://www.cs.colostate.edu/~ct310/yr2018sp/index.php'>CT 310</a> Course Project.
          </p>
        </center>
      </div>
  </footer>

</body>
</html>
