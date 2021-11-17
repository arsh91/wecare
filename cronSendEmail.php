<?php

include 'db_connection.php';

require_once('Vendor/PHPMailer/src/PHPMailer.php');
require_once('Vendor/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sendemaildata =$db->query('SELECT * FROM EmailQueue LEFT JOIN MaintenanceTicket On EmailQueue.TicketNum = MaintenanceTicket.TicketNum WHERE EmailQueue.Status= "Pending" AND EmailQueue.ScheduleDate<= now()')->fetchAll();
// echo "<pre>"; print_r($sendemaildata); echo "</pre>"; die;
$currentdatetime=date('Y-m-d H:i:s');
foreach($sendemaildata as $mail_data){
        if($mail_data["ClosedBy"] >= 1){
            $db->query('UPDATE EmailQueue SET Status = ? WHERE id = ?',"Not Sent-Closed", $mail_data['id']);
        }else if($mail_data['noFlagEmails'] != 1 && $mail_data['ETATeamMemberID']> 0){
            $db->query('UPDATE EmailQueue SET Status = ? WHERE id = ?',"Not Sent (Already Assigned to Team Member '".$mail_data['ETATeamMemberID']."')", $mail_data['id']);

        }else {
            
        $email = new PHPMailer();
        $email->SetFrom($mail_data['FromEmail']);
        $email->Subject   = $mail_data['Subject'];
        $email->Body      = $mail_data['BodyText'];
        $email->AddAddress( $mail_data['ToEmail'] );
        $email->addBcc('harpreet.developer.02@gmail.com');
        $email->isHTML(true);
        $files = json_decode($mail_data['Files']);
        
        if(!empty($files)){
            $files = array_filter($files);
            foreach($files as $file_data){
                $email->AddAttachment( dirname(__FILE__).'/'.$file_data );
            }
        }
        $email->Send();
        $db->query('UPDATE EmailQueue SET Status = ?, TimeDateSent = ? WHERE id = ?', 'Sent',$currentdatetime, $mail_data['id']);
        
        // $ticketData = $db->query('SELECT Urgency FROM MaintenanceTicket WHERE TicketNum = ?', $mail_data['TicketNum'])->fetchAll();
        // if($mail_data['Subject'] != ''){
        //     $TicketNotification = $db->query('INSERT into TicketNotification (TicketNum, TeamMemberNotified, emailaddress, Urgency) VALUES (?, ?, ?, ?)', $mail_data['TicketNum'], $mail_data['TeamMemberID'], $mail_data['ToEmail'], $ticketData[0]['Urgency']);
        // } else {
        //     $TicketNotification = $db->query('UPDATE TicketNotification SET textmagicaddress = ? WHERE TicketNum = ? AND TeamMemberNotified = ?', $mail_data['ToEmail'], $mail_data['TicketNum'], $mail_data['TeamMemberID']);
        // }
    }
}
?>
