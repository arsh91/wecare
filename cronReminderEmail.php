<?php
include 'db_connection.php';

require_once('Vendor/PHPMailer/src/PHPMailer.php');
require_once('Vendor/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// MAIL FUNCION FOR ALL EMAILS
function sendEmail($subject, $bodytext, $toEmail, $Ticket_Num, $TeamMember_Id,  $status="Pending", $schedule_datetime, $Type="reminder_email"){
    global $db;
  
    $from_email='toddknight@equisourceholdings.com';

     $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject, BodyText, ToEmail, TicketNum, TeamMemberID, Status, ScheduleDate , Type, noFlagEmails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',$from_email, $subject, $bodytext, $toEmail, $Ticket_Num, $TeamMember_Id,  $status, $schedule_datetime, $Type, '1');
    
    $updateMaintainenceReminder =  $db->query('UPDATE MaintenanceTicket SET ReminderEmail = ?  WHERE TicketNum = ?', '1', $Ticket_Num);

}

$maintainenceData =$db->query('SELECT * FROM MaintenanceTicket WHERE ClosedBy IS NULL AND  ETA_radio!= "" AND ReminderEmail!= "1" AND(TIMESTAMPDIFF(HOUR, concat(ETADate," ",ETATime), now()) BETWEEN 4 AND 168)')->fetchAll();
// echo "<pre>"; print_r($maintainenceData); echo "</pre>"; die;

foreach($maintainenceData as $reminder_data){

    $Ticket_Num = $reminder_data["TicketNum"];
    $TeamMember_Id = $reminder_data["ETATeamMemberID"];
    $teamsData = $db->query('SELECT Email, Fname FROM Team WHERE TeamMemberID = ?', $TeamMember_Id)->fetchAll();
     $Encode_Ticket_Num= base64_encode($Ticket_Num);
    $Encode_TeamMember_Id= base64_encode($TeamMember_Id);
      $link = "https://wecare.equisourceholdings.com/ticket_detail.php?ticketNum=".$Encode_Ticket_Num."&teamMemberNo=".$Encode_TeamMember_Id;
   
        foreach($teamsData as $teamData){
            $schedule_datetime = date('Y-m-d H:i:s');
            $toEmail= $teamData["Email"];
            $subject ="Please close ticket #".$reminder_data['TicketNum'].". "; 

            $bodytext = "<p>Hi ".$teamData["Fname"].",</p><p> You had listed an ETA on ticket #" .$reminder_data['TicketNum']. " of ".date("m-d-Y", strtotime($reminder_data['ETADate']) ). " on ".date("h:i A", strtotime($reminder_data['ETATime']) ). ".</p> <p> If you have completed the ticket, please take a moment to <a href='".$link."'>click here</a> in order to close the ticket: 
            </p>";
            sendEmail($subject, $bodytext, $toEmail,$Ticket_Num,$TeamMember_Id,"Pending",$schedule_datetime);
        }
}  

?>