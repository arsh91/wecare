<?php
include 'db_connection.php';

require_once('Vendor/PHPMailer/src/PHPMailer.php');
require_once('Vendor/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// MAIL FUNCION FOR ALL EMAILS
function sendEmail($subject, $bodytext, $toEmail, $Ticket_Num, $TeamMember_Id,  $status="Pending", $schedule_datetime, $Type="feedback_reminder_email"){
    global $db;
  
    $from_email='toddknight@equisourceholdings.com';

     $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject, BodyText, ToEmail, TicketNum, TeamMemberID, Status, ScheduleDate , Type, noFlagEmails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',$from_email, $subject, $bodytext, $toEmail, $Ticket_Num, $TeamMember_Id,  $status, $schedule_datetime, $Type, '1');
    
    $updateMaintainenceReminder =  $db->query('UPDATE MaintenanceTicket SET FeedbackReminderEmail = ?  WHERE TicketNum = ?', '1', $Ticket_Num);

}

$maintainenceData =$db->query('SELECT * FROM MaintenanceTicket WHERE GuestRating IS NOT NULL AND FeedbackReminderEmail!= "1" ')->fetchAll();
// echo "<pre>"; print_r($maintainenceData); echo "</pre>"; die;

foreach($maintainenceData as $reedbackReminderData){

    $Ticket_Num = $reedbackReminderData["TicketNum"];
    $TeamMember_Id = $reedbackReminderData["ETATeamMemberID"];
    $Property = $reedbackReminderData["Property"];
    $FirstName = $reedbackReminderData["FirstName"];
    $ClosedBy = $reedbackReminderData["ClosedBy"];
    $ClosedDate = $reedbackReminderData["ClosedDate"];
    $Issue = $reedbackReminderData["Issue"];
    $IssueDescription = $reedbackReminderData["IssueDescription"];
    $GuestRating = $reedbackReminderData["GuestRating"];
    $GuestFeedback = $reedbackReminderData["GuestFeedback"];
    
    $teamsData = $db->query('SELECT Email, Fname, Lname FROM Team WHERE TeamMemberID = ?', $ClosedBy)->fetchAll();
     $Encode_Ticket_Num= base64_encode($Ticket_Num);
    $Encode_TeamMember_Id= base64_encode($TeamMember_Id);
      $link = "https://wecare.equisourceholdings.com/ticket_detail.php?ticketNum=".$Encode_Ticket_Num."&teamMemberNo=".$Encode_TeamMember_Id;
   
        foreach($teamsData as $teamData){
            $schedule_datetime = date('Y-m-d H:i:s');
            $toEmail= $teamData["Email"];
            $subject ="Guest feedback received for ".$teamData['Fname']." ".$teamData['Lname']."  on ticket #".$reedbackReminderData['TicketNum']." "; 

            $bodytext ='Hi '.$teamData["Fname"].', <br> <p> A Guest has provided the following feedback for your handling of ticket #'.$reedbackReminderData["TicketNum"].':<p> <div><p>Property: '.$Property.' </p><p> Guest name: '.$FirstName.' </p><p> Date closed: '.date("m-d-Y", strtotime($ClosedDate) ).' </p><p> Category: '.$Issue.' </p><p>  Issue details: '.$IssueDescription.' </p><br><p> Guest rating: '.$GuestRating.' </p><p> Guest comments: '.$GuestFeedback.' </p><p> You can see the complete detail for this ticket  <a href='.$link.'> here</a> </p></div>';
            
            sendEmail($subject, $bodytext, $toEmail,$Ticket_Num,$TeamMember_Id,"Pending",$schedule_datetime);
        }
}  

?>