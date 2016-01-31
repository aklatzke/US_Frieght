<?php
/*
Author: David Gilliam
Contact: Davygxyz@gmail.com
GitHub: https://github.com/davygxyz
Date: 1/28/16
Project Name: US Freight Landing Page
*/


//Clear any errors
//Check if form is submitted
session_start();
if(isset($_POST['submit'])){
  $name = htmlspecialchars($_POST['name']);
  $email = $_POST['email'];
  //Email Validation
  if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
    //Error response if email is not validated
      $_SESSION['error'] = "Sorry, there seems to be a problem with your Email Address.";
      //Sends a GET variable to address bar.
      header("Location: index.php");
  }
  $number = $_POST['phn'];
  $form_message = htmlspecialchars($_POST['message']);
  //Validating Googole ReCaptcha
  if(isset($_POST['g-recaptcha-response'])){
    $reCaptch_response = $_POST['g-recaptcha-response'];
  }
  if(!$reCaptch_response){
    $_SESSION['error'] ="Please check the checkbox.";
    header("Location: index.php");
  }
  //Sending validation to googles api
  $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret='6Lfp4xYTAAAAAPpkdBSbuFxlGR-Vr0oA9l8FJIKd'&response=".$reCaptch_response));
    if($response->success==false){
      $_SESSION['error'] ="Please varify that you are not a robot.";
      header("Location: index.php");
    }else{
      session_unset();
    }

//============SENDING EMAIL====================
//Grabbing swiftmailer from vendor folder.
require_once 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';

// Create the mail transport configuration
$transport = Swift_MailTransport::newInstance();

// Create the message
$message = Swift_Message::newInstance();
//Companies Email Address will go here.
$message->setTo(array(
  $email => $name
));
//Creating new message
$message->setSubject("New Submission from USFreight.com");
$message->setBody("
<html>
  <head>
    <title>New Submission from USFreight.com</title>
  </head>
  <body>
  <table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
    </tr>
    <tr>
      <td>".$name."</td>
      <td>".$email."</td>
      <td>".$number."</td>
    </tr>
  </table>
  <h3>Message</h3>
  <p>".$form_message."<p>

  </body>
</html>
", "text/html");
// Email address and name from form.
$message->setFrom($email, $name);

// Send the email
$mailer = Swift_Mailer::newInstance($transport);
$result = $mailer->send($message);
//Check if the mailer sent or not. Will created a session to display on page.
if(!$result == false){
  $_SESSION['success'] = "Thank you for contacting us!";
}else{
  $_SESSION['error'] =" Sorry, request was not sent. Please try again.";
}

}else{
  //Will clear any sessions if the form has not been submitted.
  session_unset();
}
include"template/header.php";
?>
  <div class="col-md-offset-4 col-md-16">
    <div id='main-text'>
      <h4>A New Look. A New Design.</h4>
      <h1>Same US Freight.</h1>
      <p>
        This is an exciting time for US Freight! Please bear with us while we build our new
        website. We're designing a new site for a better experience.
      </p>
    </div>
  </div>

  <div class="col-md-12">
    <fieldset>
      <legend>Contact Us</legend>
      <div class="col-md-offset-1 col-md-22">
        <?php
        //Errors or Success goes here.
          if(isset($_SESSION['error'])){
            echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
          }
          if(isset($_SESSION['success'])){
            echo "<div class='alert alert-success' role='alert'>".$_SESSION['success']."</div>";
          }
        ?>
        <form method="POST">
          <input type="text" class="form-control" name="name" placeholder="Name" required>
          <input type="email" class="form-control" name="email" placeholder="Email Address" required>
          <input type="tel" class="form-control" name="phn" placeholder="Phone Number">
          <textarea class="form-control" rows="5" name="message" required>Message</textarea>
          <div class="g-recaptcha" data-sitekey="6Lfp4xYTAAAAAF1yvc7xJh0Md2T0x5u9LuJGSIou"></div>
          <button type="submit" name="submit" class="btn btn-custom btn-lg btn-block">Submit</button>
      </form>
      </div>
    </fieldset>
  </div>
  <div class="col-md-12">
    <fieldset>
      <legend>We're Hiring</legend>

      <div class="col-md-offset-1 col-md-22">
        <div id='secondary-text'>
          <p>We’re expanding our team and hiring individuals who are interested in a high earning potential and challenging career in logistics. If you think you have what it takes to help us grow and acheive our goals, apply today.</p>
          <p>Please submit your resume and cover letter to:</p>
          <p><a href="mailto:careers@gousfreight.com?Subject=Employment Resume" target="_top">careers@gousfreight.com</a></p>
        </div>
      </div>
    </fieldset>
  </div>


<?php include"template/footer.php"; ?>
