<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	include 'db_connection.php';
    
    require_once('Vendor/PHPMailer/src/PHPMailer.php');
    require_once('Vendor/PHPMailer/src/Exception.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

     // MAIL FUNCION FOR ALL EMAILS
        function sendEmail($subject, $bodytext, $toEmail, $schedule_datetime="", $file1="", $file2="", $status="Pending", $noFlagEmails="0", $Type="lost_found_email"){
        global $db;
      
        $from_email='toddknight@equisourceholdings.com';
        $all_files=json_encode(array($file1,$file2));

         $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject, BodyText, ToEmail, Files, Status, ScheduleDate , noFlagEmails, Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',$from_email, $subject, $bodytext, $toEmail,$all_files, $status,$schedule_datetime,$noFlagEmails,$Type);

    }
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php 
if(isset($_POST['submit'])){

     // form fields
     $property= $_POST['property'];
     $firstname= $_POST['firstname'];
     $lastname= $_POST['lastname'];
     $checkoutdate= $_POST['checkoutdate'];
     $phone= $_POST['phone'];
     $email= $_POST['email'];
     $shippingaddress= $_POST['shippingaddress'];
     $shippingcity= $_POST['shippingcity'];
     $shippingstate= $_POST['shippingstate'];
     $Zip= $_POST['Zip'];
     $discriptionlostitem= $_POST['discriptionlostitem'];
     $bestguess= $_POST['bestguess'];
     $shipping_method_radio= $_POST['shipping_method_radio'];
    $subject = "LOST AND FOUND ".$discriptionlostitem. ', ' .$property. ', ' .$checkoutdate;

     
     $DuplicateLostFoundData =$db->query('SELECT * FROM LostAndFound WHERE Property =? AND FirstName=? AND LastName=?  AND Email=? AND ShippingAddress=? AND ShippingCity	=? AND ShippingState=? AND Zip=? AND DescriptionLostItem=? AND BestGuess=? AND ShippingMethod=?',$property, $firstname, $lastname, $email, $shippingaddress, $shippingcity, $shippingstate, $Zip, $discriptionlostitem, $bestguess, $shipping_method_radio)->fetchArray();

     if(!empty($DuplicateLostFoundData)) {  ?>
    <section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0">
                        <div class="card-header text-center">
                             Already Exists!!
                        </div>
                        <div class="card-body text-center thankyou_card">
                            This Data has been submitted and received. There is no need to
                            resubmit
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    }
    else
    { 

    $file1 = $file2 = "";

    if($_FILES["file1"]["tmp_name"] != "" ){
    $filename = $_FILES["file1"]["name"];
    $tempname = $_FILES["file1"]["tmp_name"];
    $file1 = "ccimages/a".time().$filename;
    uploadFile($tempname, $file1);
    }
    if($_FILES["file2"]["tmp_name"] != "" ){
    $filename = $_FILES["file2"]["name"];
    $tempname = $_FILES["file2"]["tmp_name"];
    $file2 = "ccimages/b".time().$filename;
    uploadFile($tempname, $file2);
    }
  
   
   
		$lostFoundData = $db->query('INSERT into LostAndFound (Property, FirstName, LastName, CheckOutDate, Phone, Email, ShippingAddress, ShippingCity, ShippingState, Zip, DescriptionLostItem, BestGuess, ShippingMethod, Pic1, Pic2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $property, $firstname, $lastname, $checkoutdate, $phone, $email, $shippingaddress, $shippingcity, $shippingstate, $Zip, $discriptionlostitem, $bestguess, $shipping_method_radio, $file1, $file2);
        
           
          ?>
    <section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                    <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0">
                        <div class="card-header text-center">
                      
                        </div>
                        <div class="card-body text-center">
                            <p>  We’re sorry that you may have left an item.
                            </p>
                            <p> The information about your item has 
                            been received.
                            </p>
                            <p>We’ll search for tem for you during 
                                our next vacancy and will provide text 
                                updates to <?= $phone; ?> as additional 
                                information becomes available.
                            </p>
                        </div>
                        <div class="card-footer text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
   //USER EMAIL
    $phoneEmail = "1".$phone."@textmagic.com";
    $schedule_datetime = date('Y-m-d H:i:s');
    $bodytext = "<p>Hi ".$firstname." , We’re sorry that you left an item at ".$property .".  But we will do our best to locate it 
    for you.  We will text you as additional information becomes available.</p>";
    sendEmail("", $bodytext, $phoneEmail,$schedule_datetime,"","","Pending","1");

    //TEAM MEMBER EMAIL
    // $teamMemberEmail="golfcartagreements@gmail.com , steven.todd.knight@gmail.com";
    $teamMemberEmails = array(
        "golfcartagreements@gmail.com",
        "steven.todd.knight@gmail.com"
        );

    $bodytext ='Hey, <br> <p> check find  lost and found page details:- <p> <div><p>Property Name = '.$property.' </p><p> First Name = '.$firstname.' </p><p> Last Name= '.$lastname.' </p><p> Check Out Date = '.$checkoutdate.' </p> <p>  Phone = '.$phone.' </p> <p> Email = '.$email.' </p><p> Shipping Address = '.$shippingaddress.' </p><p> Shipping City = '.$shippingcity.' </p><p> Shipping State = '.$shippingstate.' </p><p> Zip  = '.$Zip.' </p><p> Description of Lost Item = '.$discriptionlostitem.' </p><p> Best guess at where item was left = '.$bestguess.' </p><p> Shipping method = '.$shipping_method_radio.' </p></div>';
    
    foreach($teamMemberEmails as $teamMemberEmail){
        sendEmail($subject, $bodytext, $teamMemberEmail, $schedule_datetime, $file1, $file2);
    }
   
    }
       
}

function uploadFile($tempname, $folder){
    if (move_uploaded_file($tempname, $folder))  {
        return true;
    }else{
        return false;
    }
}
?>
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
