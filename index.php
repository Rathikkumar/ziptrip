<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$error = '';
$name = '';
$department = '';
$email = '';
$company = '';
$message = '';

function clean_text($string)
{
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

if (isset($_POST["submit"])) {
    $department = $_POST['department'];

    if (empty($_POST["name"])) {
        $error .= 'Please Enter your Name<br>';
    } else {
        $name = clean_text($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $error .= 'Only letters and white space allowed in the Name<br>';
        }
    }
    if (empty($_POST["email"])) {
        $error .= 'Please Enter your Email<br>';
    } else {
        $email = clean_text($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error .= 'Invalid email format<br>';
        }
    }
    if (empty($_POST["company"])) {
        $error .= 'Company is required<br>';
    } else {
        $company = clean_text($_POST["company"]);
    }
    if (empty($_POST["message"])) {
        $error .= 'Message is required<br>';
    } else {
        $message = clean_text($_POST["message"]);
    }

    if ($error == '') {
        $file_open = fopen("contact_data.csv", "a");
        $no_rows = count(file("contact_data.csv"));
        if ($no_rows > 1) {
            $no_rows = ($no_rows - 1) + 1;
        }
        $form_data = array(
            'sr_no' => $no_rows,
            'name' => $name,
            'email' => $email,
            'company' => $company,
            'message' => $message,
            'department' => $department
        );
        fputcsv($file_open, $form_data);

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rathikkumar3084@gmail.com'; //your Gmail
        $mail->Password = 'npuesrsuwkgblxrp'; //your Gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('rathikkumar3084@gmail.com'); //your Gmail

        $mail->addAddress($_POST["email"]); 

        // ...
        $mail->isHTML(true);

       // Read the email template file
       $email_template = file_get_contents('template.html');

       $email_template = str_replace('{name}', $name, $email_template);
       $email_template = str_replace('{email}', $email, $email_template);
       $email_template = str_replace('{company}', $company, $email_template);
       $email_template = str_replace('{message}', $message, $email_template);
       $email_template = str_replace('{department}', $department, $email_template);

       $mail->Subject = 'New Form Submission';
       $mail->Body = $email_template;

       // ...

        try {
            $mail->send();
            $success_message = 'Sent Successfully';
        } catch (Exception $e) {
            $error = 'Error sending email: ' . $mail->ErrorInfo;
        }
    }
    // connecting to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "contacts";
    
    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Die if connection was not successful
    if (!$conn){
     die("Sorry we failed to connect: ". mysqli_connect_error());
    }
    else{ 
       
        // submit the database

    // Sql query to be executed
     $sql = "INSERT INTO `contactus` ( `name`, `department`, `email`, `company`, `message`) VALUES ('$name', '$department', '$email', '$company ', '$message')";
     $result = mysqli_query($conn, $sql); 
    
     if($result) { 
       
    }
    else{
    echo "The record was not inserted successfully because of this error ---> ". mysqli_error 
    ($conn);
       }
    }
    
}
?>
   


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" rel="stylesheet">
    
         <title>Contact Us</title>
        
    </head>
<body>
    <nav class=" navbar navbar-expand-lg navbar-light bg-light">
        <div class="container" >
          <a class="navbar-brand mt-4" href="#">
            <img src="blackLogo 2 - Copy.svg" alt="" width="115" height="32">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="container   mt-4 collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item ">
                <a class="nav-link" href="./home-page-ziptrrip.html">Home</a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link ps-4 " href="./home-page-ziptrrip.html#AboutUs">About Us</a>
              </li>
              
              <li class="nav-item ps-4  ">
                <a class="nav-link" href="./team.html">Team</a>
              </li>
              
              <li class="nav-item ps-4 ">
                <a class="nav-link mb-0 h5 ps-5 text-dark" href="#">Contact Us</a>
              </li>
              
              <li class="nav-item ps-4 ">
                <a class="nav-link" href="./home-page-ziptrrip.html#Customers">Customers</a>
              </li>

            </ul>
            <div class="d-flex justify-content-center align-items-center gap-3">
                <a href="#Login" class="text-dark text-decoration-none px-3 py-1" >
                    <button type="button" class="btn btn-outline-secondary">Login</button></a>
                    <div class=" px-3 py-1" style="background-color: rgba(2, 233, 205, 1);">
                        <button type="button" class="btn text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#myModal">Request a Demo</button>
                      <div class="modal" id="myModal">
                        <div class="modal-dialog  modal-lg modal-dialog-centered">
                            <div class="modal-content ">
                                <div class="container">
                                    <h2>Schedule a Demo</h2>
                                    <p>
                                        Please share your contact details for us, to get in touch with you at the earliest.
                                    </p>
                                    
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <img src="user-01.svg" alt="Image" class="placeholder-image">
                                            </span>
                                            <input type="text" class="form-control" name="name" id="name"  placeholder="Full name">
                                         </div><br>
                                    
                                         <div class="input-group">
                                            <span class="input-group-text">
                                                <img src="phone.svg" alt="Image" class="placeholder-image">
                                            </span>
                                            <input type="tel" class="form-control" name="mobile" id="mobile" pattern="[0-9]{10}" placeholder="Mobile No.">
                                        
                                        </div><br>
                                    
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <img src="mail-01.svg" alt="Image" class="placeholder-image">
                                        </span>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email ID">
                                    
                                    </div><br>
                                    
                                    
                                    <div class="d-grid">
                                        <input type="submit" value="Submit" class="btn rounded-0 py-2 px-4 text-white" style="background-color: rgba(2, 233, 205, 1);">
                                        <span class="submitting"></span>
                                    </div><br>
                                    </div>
                            </div>
                        </div>
                      </div>
                    </div>
            </div>


          </div>
        </div>
      </nav>
      <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mr-auto mt-5">
                    <h2>Contact Us</h2><br>
                    <p class="mb-0">One solution for all your travel bookings & expense management solutions.</p>
                    <p class="mb-0"> With a single tool seamlessly digitize at your travel bookings as well as expense reimbursements.</p>
                </div>
                <div class="col-md-6 mt-5">
                 <form class="mb-6" method="POST" action="index.php" id="contactForm" name="contactForm" novalidate="novalidate">
                    <div class="row">
                    <?php echo $error; ?>
                        <div class="col-md-12 form-group">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="help-circle.svg" alt="Image" class="placeholder-image">
                                </span>
                                <select class="form-select text-muted" name="department" id="department">
                                    <option value=" " disabled selected >How can we help you?</option>
                                    <option value="hr">Choose a smart booking tool.</option>
                                    <option value="sales">Assure 24/7 travel support.</option>
                                    <option value="it">Organize and utilize travel spend data in real-time.</option>
                                    <option value="marketing">Organize and utilize travel spend data in real-time.</option>
                                </select>
                            </div>
                        </div>
                    </div><br>
                    
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="user-01.svg" alt="Image" class="placeholder-image">
                                </span>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Full name" value="<?php echo $name; ?>">
                            </div>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="briefcase-01.svg" alt="Image" class="placeholder-image">
                                </span>
                                <input type="text" class="form-control" name="company" id="company" placeholder="Company name" value="<?php echo $company; ?>">
                            </div>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="mail-01.svg" alt="Image" class="placeholder-image">
                                </span>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Company Email ID" value="<?php echo $email; ?>" >

                            </div>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <div class="input-group">
                            <span class="input-group-text">
                                <img src="message-text-circle-01.svg" alt="Image" class="placeholder-image">
                            </span>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="5" placeholder="Comment"> <?php echo $message; ?></textarea>
                        </div>
                    </div>
                    </div><br>

                    <div class="row">
                        <div class="col-12">
                        <input type="submit" name="submit" class="btn rounded-0 py-2 px-4 text-white" style="background-color: rgba(2, 233, 205, 1);" value="Submit" />
                            <span class="submitting"></span>
                        </div>
                    </div>
                    

                 </form>
                </div>
            </div>
        </div>
      </div>


      <div class="container mt-5 pt-5"><br><br>
        <div class="row row-cols-3 g-2">
            <div class="col">
                <div class="p-3" style="background-color: rgba(4, 38, 125, 0.1);">
                    <b>Talk to us on</b><br><br>
                    <p class="text-muted">
                        <a href="tel:+919876543210" style="text-decoration: none; color: inherit;">+91 9876543210</a>
                    </p>
                </div>
                
                
            </div>
            <div class="col">
                <div class="p-3" style="background-color: rgba(255, 64, 64, 0.1);">
                    <b>Talk to our executive at</b><br><br>
                    <p class="text-muted">
                        <a href="mailto:sales@ziptrrip.org" style="text-decoration: none; color: inherit;">sales@ziptrrip.org</a>
                    </p>
                </div>
                
                
            </div>
            <div class="col">
                <div class="p-3" style="background-color: rgba(207, 149, 216, 0.1);">
                    <b>Come to see us at</b><br>
                    <p class="text-muted">
                        <a href="https://www.google.com/maps?q=B-903, Plot-09, Sai Saakshaat, Sec-6 Kharghar, Raigarh, Raigad, Maharashtra, 410210" target="_blank" style="text-decoration: none; color: inherit;">B-903, Plot-09, Sai Saakshaat, Sec-6 Kharghar, Raigarh, Raigad, Maharashtra, 410210</a>

                    </p>
                </div>
                
                
            </div>
            <!-- Add more columns as needed -->
        </div>
    </div>

    <footer class="text-center text-lg-start  pt-4  mt-5 "><br><br><br><br><br>
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom bg-image bg-light" style="background-image: url('Frame 1000002012.svg'); background-size: cover; background-repeat: no-repeat; ">
    
            <div class="container ">
                  <div class="container bg-image" >
                    <div class="row">
                      <div class="col-lg-6 col-md-12 mb-4"><br><br>
                        <a class="navbar-brand mt-4 " href="#">
                            <img src="blackLogo 2 - Copy.svg" alt="" class="img-fluid" width="200" height="50">
                          </a>
                          <div class="mt-3">
                            <p1 class=" fs-4">The everything platform for corporate travel.</p1>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-6 mb-4 " >
                            <img src="Group 1000002015.svg" width="250" height="250" alt="Frame 1000002429">
        
                      </div>
                      <div class="col-lg-3 col-md-6 mb-4"><br><br>
                        <p1 class=" fs-4 "> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Download over app on</p1>
                        <div class="container-fluid"><br>
                            <div class="row">
                              <div class="col">
                                <a href="#">
                                    <img src="google-play-badge 1 - Copy.svg" class="w-100" alt="Google Play Badge">
                                </a>
                                
                              </div>
                              <div class="col order-5">
                                <a href="#">
                                <img src="Download_on_the_App_Store_Badge_US-UK_RGB_blk_092917 - Copy.svg"  class="w-100" alt="" ></a>
                              </div>
                            </div>
                          </div>
                        </table>
                      </div>
                    </div>
                  </div>
        </section>
      
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom bg-image  "
         style="background-image: url('Frame 1000002012.svg'); background-size: cover; background-repeat: no-repeat;background-color: rgba(38, 50, 56, 1); " >
    
            <div class="container mt-5">
                <div class="row">
                  <div class="col-lg-6 ">
                    <h1 class="text-white"> Schedule a Demo</a> </h1>
                    <p class="fs-4 text-white">Sign up to Schedule a Demo </p>
    
                    <div class="form-outline form-white mb-4 mt-5">
                        <div class="input-group">
                            <input type="email" id="form5Example21" class="form-control" placeholder="Enter your email" />
                            <button class="btn btn-light rounded-end" type="button" id="sendEmailButton">
                                <img src="Frame 1000002335.svg" alt="Send"  width="40" height="40">
                            </button>
                        </div>
                    </div>
                 </div>
                   <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left">
                      <img src="19948561_6157508 1.svg" class="rounded float-end " alt="19948561_6157508">
                    </div>
                  
                  </div>
              </div>
        </section>
      
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom   "style="background-color: rgba(2, 233, 205, 0.15);" >
    
            <div class="container mt-5">
                <div class="row">
                  <div class="col-lg-6 ">
                    <a class="navbar-brand mt-4 " href="#">
                        <img src="blackLogo 2 - Copy.svg" alt="" class="img-fluid" width="120" height="120">
                      </a>
                      <div class="mt-3">
                        <h6 >The Everything platform for corporate travel.</h6><br>
                        <h6>B-903, Plot-09, Sai Saakshaat, Sec-6 Kharghar,</h6> 
                         <h6> Raigarh, Raigad, Maharashtra, 410210 </h6>
                      </div><br><br>
    
                      <div>
                        <a href="#" class="me-4 link-dark btn btn-outline-dark btn-floating  rounded-circle">
                            <i class="fab fa-facebook-f"></i>
                        </a>
    
                        <a href="" class="me-4 link-dark btn btn-outline-dark btn-floating  rounded-circle">
                            <i class="fab fa-instagram"></i>
                          </a>
                        
                        <a href="" class="me-4 link-dark btn btn-outline-dark btn-floating  rounded-circle">
                          <i class="fab fa-twitter"></i>
                        </a>
                       
                        <a href="" class="me-4 link-dark btn btn-outline-dark btn-floating  rounded-circle">
                          <i class="fab fa-linkedin"></i>
                        </a>
                       
                      </div>
    
                  
                 </div>
                   <div class="col-lg-6 " data-aos="fade-left">
                    <div class="row  ">
                        <div class="col-4 ">
                         <div class="  col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-capitalize fw-bold mb-4">
                                Resources
                              </h6>
                              <p>
                                <a href="#!" class="text-reset"style="text-decoration: none;"> FAQs </a>
                              </p>
                              <p>
                                <a href="#!" class="text-reset" style="text-decoration: none;">Privacy</a>
                            </p>
                            
                        </div>
                        </div>
                        <div class="col-4  ">
                          <div class="  col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <h6 class="text-capitalize fw-bold mb-4">
                                Corporate
                              </h6>
                              <p>
                                <a href="#!" class="text-reset" style="white-space: nowrap; text-decoration: none;">Our Customers</a>
    
                              </p>
                              <p>
                                <a href="#!" class="text-reset "style="white-space: nowrap; text-decoration: none;" >Customer Stories</a>
                              </p>
                          </div>
                        </div>
                        <div class="col-4">
                            <div  class="  col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4" >
                                <h6 class="text-capitalize fw-bold mb-4">
                                    Company
                                  </h6>
                                  <p>
                                    <a href="#!" class="text-reset" style="white-space: nowrap; text-decoration: none;">About Ziptrrip</a>
                                </p>
                                
                                  <p>
                                    <a href="#!" class="text-reset" style="white-space: nowrap;text-decoration: none;">Contact Us</a>
                                  </p>
    
                             </div><br><br><br>
                             <div class="row">
                                <div class="col">
                                  <a href="#">
                                      <img src="google-play-badge 1 - Copy.svg" class="w-100" alt="Google Play Badge">
                                  </a>
                                  
                                </div>
                                <div class="col order-5">
                                  <a href="#">
                                  <img src="Download_on_the_App_Store_Badge_US-UK_RGB_blk_092917 - Copy.svg"  class="w-100" alt="" ></a>
                                </div>
                              </div>   
                        </div>
                    </div>
                    </div>
                    <hr class="my-4 bg-dark " style="border-top: 3px solid #000;">
    
                    <div class="container d-md-flex py-4">
                        <div class="me-md-auto text-center text-dark text-md-start">
                            <div class="copyright">
                                © Ziptrrip 2023. All rights reserved.
                            </div>
                        </div>
                        <div class=" text-center text-md-right pt-3 pt-md-0">
                            <div class="row">
                                <div class="col">
                                    <p>
                                        <a href="#!" class="text-reset" style="white-space: nowrap; text-decoration: none;">Privacy Policy</a>
                                    </p>
                                  
                                </div>
                                <div class="col order-5">
                                    <p>
                                        <a href="#!" class="text-reset" style="white-space: nowrap; text-decoration: none;">Terms and Conditions</a>
                                    </p>
                              </div> 
                        </div>
                    </div>
    
                 </div>
            </div>
        </section>
     </footer>
<!-- Success Message Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Add the modal-dialog-centered class here -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo $success_message; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($success_message)): ?>
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
<?php endif; ?>

          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>