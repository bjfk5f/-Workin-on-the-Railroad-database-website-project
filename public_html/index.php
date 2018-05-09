<?php
   //include('secure/database.php');
//   $link = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME) or die("Connect error " . mysqli_error($link));
    $link = mysqli_connect("localhost", "group12", "2245", "group12") or die("Connect error " . mysqli_error($link));
    $mysqli = $link;
?>
<!doctype html>
<html lang="">

<head>
   <meta charset="utf-8">
   <title>Workin' on the Railroad</title>
   <link rel="stylesheet" href="web/resources/css/style.css">
   <link rel="stylesheet" href="web/resources/css/landing.css">

   <!-- load javascript resources -->
   <script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
   <script type="text/javascript" src="web/resources/js/script.js"></script>

   <!-- load css framework resources -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/css/materialize.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/js/materialize.min.js"></script>

</head>

<body>
   <div class = "landing-wrapper">
   <div class = "hi z-depth-2">
      <h2>Welcome</h2>
         <p>to</p>
         <h2>ChuChu Industries</h2>
         <h5> Where Rubber Meets <br>The Rail</h5>
         <span><a class="waves-effect waves-light btn-large btn-right z-depth-3" href="web/register.php">Register</a></span>
         <a class="waves-effect waves-light btn-large btn-right modal-trigger z-depth-3 " data-target="modal1">Login</a><br>
         <span><a class="waves-effect waves-light btn-large btn z-depth-3" href="web/guest.php">Proceed as Guest</a></span>
   </div>
    </div>
   <div class = "container form-signin">
      <!-- php for login to sessions later if we want to use that -->
      <?php
         $message = "";
         session_start();

         if(isset($_POST['submit'])){

            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $select_query = "SELECT password, role FROM authentication WHERE user_id=?;";
            $select_stmt = $link->prepare($select_query);
            $select_stmt->bind_param("s", $username);
            $select_stmt->execute();
            $result = $select_stmt->get_result();

            if ($row = $result->fetch_assoc()) {

               $stored_password = $row["password"];

               if (password_verify($password, $stored_password)){
                  $message = "Welcome " . $username;

                  $user_select_query = "SELECT user_id, first_name, last_name FROM user WHERE user_id =?;";
                  $user_select_stmt = $link->prepare($user_select_query);
                  $user_select_stmt->bind_param("s", $username);
                  $user_select_stmt->execute();
                  $user_result = $user_select_stmt->get_result();
                  if ($user_row = $user_result->fetch_assoc()) {
                     $_SESSION['first_name'] = $user_row["first_name"];
                     $_SESSION['last_name'] = $user_row["last_name"];
                  }
                  $_SESSION['username'] = $username;
                  $_SESSION['type'] = $row["role"];
                  $_SESSION['just_registered'] = false;
                   
                   //logging
                   $action = "User Login";
                   include "web/weblog.php";

                  header("Location: web/home.php");
               }
               else{
                  $message = "Incorrect! The username or password is not valid";
               }
            }
            else{
               $message = "Incorrect! The username or password is not valid";
            }
         }
      ?>
   </div>
   <!-- Modal Structure -->
   <div id="modal1" class="modal">
    <div class="modal-content">
      <h4 class="center">Login into your account, please</h4>
      <div class="section"></div>

      <div class="container center">
        <div class="z-depth-1 grey lighten-4 row login-container">

          <form id = "loginform" class="col s12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            <div class='row'>
              <div class='col s12'>
               <div class="message"><?php if($message!="") { echo $message; } ?></div>
              </div>
            </div>

            <div class='row'>

              <div class='input-field col s12 left-align'>
                <input type='text' name='username' id='username' />
                <label for='username'>Enter your username:</label>
              </div>
            </div>

            <div class='row'>

              <div class='input-field col s12 left-align'>
                <input class='validate' type='password' name='password' id='password' />
                <label for='password'>Enter your password:</label>
              </div>
            </div>

            <br />
            <center>
               <div class='row'>
                  <button id = "submit" name='submit' class='col s12 btn btn-large waves-effect btn-left' >Login</button>
              </div>
            </center>
          </form>
        </div>
    </div>
   </div> <!-- /container -->
    <div class="modal-footer">
      <a href="#!" class=" modal-action modal-close waves-effect waves-red btn-flat">Dismiss</a>
   </div>
   </div>
</body>
</html>
