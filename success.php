<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	include 'db_connection.php';
    
    require_once('Vendor/PHPMailer/src/PHPMailer.php');
    require_once('Vendor/PHPMailer/src/Exception.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // FUNCTION TO UPLOAD THE FILES 
    function uploadFile($tempname, $folder){
        if (move_uploaded_file($tempname, $folder))  {
            return true;
        }else{
            return false;
        }
    }

    // FETCHING THE DATA FROM TEAM ON THE BASIS OF TEAM MEMBER ID
    function selectTeamData($teamMemberId){
        global $db;
        $Team_data = $db->query('SELECT * FROM Team WHERE TeamMemberID = ?', $teamMemberId)->fetchArray();
        return $Team_data;
    }

    // MAIL FUNCION FOR ALL EMAILS
    function sendEmail($subject, $bodytext, $toEmail, $ticketNum="", $schedule_datetime="", $teamMemberId=0, $file1="", $file2="", $file3="", $file4="",$status="Pending"){
        global $db;
        $ticket_id = base64_encode($ticketNum);
        $team_memberId= base64_encode($teamMemberId);
        $from_email='toddknight@equisourceholdings.com';
        $all_files=json_encode(array($file1,$file2,$file3,$file4));
                
         $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject,  BodyText, ToEmail, TicketNum, TeamMemberID, Files, Status, ScheduleDate ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',$from_email, $subject, $bodytext, $toEmail, $ticketNum, $teamMemberId,$all_files,$status,$schedule_datetime);

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
    $phone= $_POST['phone'];
    $satisfied_radio= $_POST['satisfied_radio'];
    $issue= $_POST['issue'];
    $IssueDescription= $_POST['IssueDescription'];
    $current_date = date("Y-m-d");
    $current_time = date("H:i:s");
    $subject = $property. ', ' .$satisfied_radio. ', ' .$issue;
    $propertyId = $_POST['propertyid'];
    $CategoryId = $_POST['categoryid'];
 

    $Duplicatetickets =$db->query("SELECT * FROM MaintenanceTicket WHERE FirstName =? AND Phone= ? AND Issue=? AND IssueDescription =? AND TicketDate >= date(NOW()) - INTERVAL 7 DAY" , $firstname,$phone, $issue,$IssueDescription)->fetchArray();
    
    if(!empty($Duplicatetickets))
    {  ?>
    <section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0">
                        <div class="card-header text-center">
                            Ticket Already Exists!!
                        </div>
                        <div class="card-body text-center thankyou_card">
                            This ticket has been submitted and received within the last 7 Days. There is no need to
                            resubmit
                        </div>
                        <div class="card-footer text-center">
                            
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
    $file1 = $file2 = $file3 = $file4 = "";

    if($_FILES["file1"]["tmp_name"] != "" ){
    $filename = $_FILES["file1"]["name"];
    $tempname = $_FILES["file1"]["tmp_name"];
    $file1 = "IssuesImg/a".time().$filename;
    uploadFile($tempname, $file1);
    }
    if($_FILES["file2"]["tmp_name"] != "" ){
    $filename = $_FILES["file2"]["name"];
    $tempname = $_FILES["file2"]["tmp_name"];
    $file2 = "IssuesImg/b".time().$filename;
    uploadFile($tempname, $file2);
    }
    if($_FILES["file3"]["tmp_name"] != "" ){
    $filename = $_FILES["file3"]["name"];
    $tempname = $_FILES["file3"]["tmp_name"];
    $file3 = "IssuesImg/c".time().$filename;
    uploadFile($tempname, $file3);
    }
    if($_FILES["file4"]["tmp_name"] != "" ){
    $filename = $_FILES["file4"]["name"];
    $tempname = $_FILES["file4"]["tmp_name"];
    $file4 = "IssuesImg/d".time().$filename;
    uploadFile($tempname, $file4); 
    }

    // INSERT THE DATA WHEN THE FORM IS SUBMIT AND STORE INTO THE MAINTAINANCE TICKET TABLE
    $dataRet = $db->query('INSERT into MaintenanceTicket (TicketDate, TicketTime, Property, FirstName, Phone, Urgency, Issue, property_Id, Category_Id ,IssueDescription, Pic1, Pic2, Pic3, Pic4 ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $current_date, $current_time, $property, $firstname, $phone, $satisfied_radio, $issue, $propertyId, $CategoryId, $IssueDescription, $file1, $file2, $file3, $file4);

    $ticketNum = $db->lastInsertID();

    // CHECK THE MAINTAINENCE CATEGORY AND FETCHING THE DATA ACCORDING TO CATEGORY NAME
    $Category_data = $db->query('SELECT * FROM MaintenanceAssignements WHERE PropertyID = ? AND CategoryID = ?', $propertyId, $CategoryId)->fetchArray();
    $ticket_id = base64_encode($ticketNum);

    // CHECK THE CONTACT PERSON FROM THE TABLE ON THE BASIS OF CATEGORY AND INSERT DATA AND SENDING EMAILS 
    $contactUserFound = false;
    $schedule_datetime = date('Y-m-d H:i:s');
    for ($i=1; $i<=10; $i++){
        if($Category_data['Contact'.$i] > 0){
            $team_data = selectTeamData($Category_data['Contact'.$i]);

            if(!empty($team_data)){
                $contactUserFound = true;
                $phoneEmail = "1".$team_data['Phone']."@textmagic.com";
                $team_memberId= base64_encode($Category_data['Contact'.$i]);

                $link = "https://wecare.equisourceholdings.com/ticket_detail.php?ticketNum=".$ticket_id."&teamMemberNo=".$team_memberId;

                $phoneLink = "https://wecare.equisourceholdings.com/ticket_detail.php?ticketNum=".$ticket_id."&teamMemberNo=".$team_memberId."&phone=1";


                
                if(isset($Category_data['Wait'.$i]) && $Category_data['Wait'.$i] > 0){

                      $addTime = $Category_data['Wait'.$i];
                     //display the converted time
                      $schedule_datetime = date('Y-m-d H:i:s',strtotime('+'.$addTime.' minutes',strtotime($schedule_datetime)));
                }

                // Email to Team Member
                $bodytext ='Click for details: <a href="'.$link.'"> '.$link.' </a>';
                sendEmail($subject, $bodytext, $team_data['Email'], $ticketNum, $schedule_datetime, $Category_data['Contact'.$i], $file1, $file2, $file3, $file4);
                $textMagic = "";
                
                // code change
                
                if($satisfied_radio != "Immediate") {
                    $allTime = false;
                    $currentday = date("l", strtotime(date('Y-m-d H:i:s')));

                    $fromColName = strtolower($currentday).'_from';
                    $toColName = strtolower($currentday).'_to';


                    if($team_data[$fromColName] == 'All' || $team_data[$toColName] == 'All' || $team_data[$fromColName] == '' || $team_data[$toColName] == ''){
                        $allTime = true;
                    } else {
                        $from_time = $team_data[$fromColName];
                        $to_time = $team_data[$toColName];
                    }

                    date_default_timezone_set("America/Chicago");
                    $current_time = date("H");
                    if($allTime  || ($current_time >= $from_time && $current_time <= $to_time) ) {    
                       // Text Message to Team Member THrough Email
                        $bodytext = $property. '<br />' .$satisfied_radio. '<br />' .$issue.'<br /> &nbsp; &nbsp; <br /><br />'.'Click for details: '.$phoneLink;
                        sendEmail('', $bodytext, $phoneEmail, $ticketNum, $schedule_datetime, $Category_data['Contact'.$i], $file1, $file2, $file3, $file4);
                    }else{
                        sendEmail('', $bodytext, $phoneEmail, $ticketNum, $schedule_datetime, $Category_data['Contact'.$i], $file1, $file2, $file3, $file4,"Not Sent (Not within text preference timeframes)");
                     }
                }

                 else {
                     // Text Message to Team Member THrough Email
                     $bodytext = $property. '<br />' .$satisfied_radio. '<br />' .$issue.'<br /> &nbsp; &nbsp; <br /><br />'.'Click for details: '.$phoneLink;
                     sendEmail('', $bodytext, $phoneEmail, $ticketNum, $schedule_datetime, $Category_data['Contact'.$i], $file1, $file2, $file3, $file4);
                     $textMagic = $phoneEmail;
                }
            }
        }

        
    }
        // TEXT MESSAGE SEND TO USER WHO SUBMITTED THE FEEDBACK
        if(trim($phone) != ""){
            // $sendemailflag = true;
        $UserPhoneEmail = "1".$phone."@textmagic.com"; 
        if($satisfied_radio == "Immediate") {
            $bodytext = "<p>Thank you for contacting us ".$firstname.".</p><p>We have received your message regarding the ".$issue. " issue at ".$property. ".</p> <p> We care about your experience.</p> <p>A member of our team will be contacting you within 60 minutes to assist you.</p> <p>Thank you for your patience and understanding.</p> <p> Todd </p>";
        }
            else if($satisfied_radio == "Today" || $satisfied_radio == "Tomorrow") 
            {     
                // $sendemailflag = false;
            $bodytext = "<p>Thank you for contacting us ".$firstname.".</p><p>We have received your message regarding the ".$issue. " issue at ".$property. ".</p> <p> We care about your experience.</p> <p>A member of our team will be contacting you within 2 hours to assist you.</p> <p> Thank you for your patience and understanding.</p> <p> Todd </p>";
        
            }
        else if($satisfied_radio == "Turn") {
            // $sendemailflag = false;
            $bodytext = "<p>We have received your message ".$firstname.".</p><p>  We greatly appreciate you taking the time to let us know about issues that need to be addressed at ".$property. " after your checkout.</p> <p>Your feedback has been very valuable.</p><p> Please enjoy your stay! </p> <p> Todd </p>";
        }

        // if($sendemailflag){
            $schedule_datetime = date('Y-m-d H:i:s');
        sendEmail('', $bodytext, $UserPhoneEmail, $ticketNum, $schedule_datetime );
        // }
    }
    
if($contactUserFound == false){ ?>
    <div class="alert alert-danger text-center" role="alert">
        Sorry, We can not find any team member for your issue.
    </div>
    <?php
}
  if ($dataRet) {
            
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
                            Thank you!
                        </div>
                        <div class="card-body text-center thankyou_card">
                            <?php if($satisfied_radio == "Turn") {?>
                            <p>We have received your message.
                            </p>
                            <p> Thank you for letting us know. </p>
                            <p>These items will be added
                                to our maintenance list after your checkout so that you will not be disturbed with their
                                repairs during your stay.
                            </p>
                            <?php } else if($satisfied_radio == "Immediate") {?>
                            <p>We have received your emergency message. </p>
                            <p> Thank you for letting us know about this
                                issue.</p>
                            <p>We are routing the message to the person responsible and will respond to you within the
                                next 60 minutes
                            </p>
                            <?php } else if($satisfied_radio == "Today" || $satisfied_radio == "Tomorrow") { ?>
                            <p>We have received your message. </p>
                            <p> Thank you for letting us know about this
                                issue.</p>
                            <p>
                                We are routing the message to the person responsible and will respond to you within
                                the next 2 hours
                            </p>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-center">
                            <p>Thank you and please enjoy your stay!<p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
        }
        else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
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
