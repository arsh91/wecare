<?php

include 'db_connection.php';

require_once('Vendor/PHPMailer/src/PHPMailer.php');
require_once('Vendor/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sendemaildata =$db->query('SELECT * FROM EmailQueue LEFT JOIN MaintenanceTicket On EmailQueue.TicketNum = MaintenanceTicket.TicketNum WHERE EmailQueue.Status= "Pending" AND EmailQueue.ScheduleDate<= now()')->fetchAll();
// echo "<pre>"; print_r($sendemaildata); echo "</pre>"; 
$currentdatetime=date('Y-m-d H:i:s');
foreach($sendemaildata as $mail_data){
    
        $sendEmail = true;
        $status = '';
        if($mail_data['Type'] == 'request_feedBack_email'){
            $sendEmail = true;
           
        }else if($mail_data["ClosedBy"] >= 1){
            $sendEmail = false;
            $status = 'Not Sent-Closed';    
        }else if($mail_data['noFlagEmails'] != 1 && $mail_data['ETATeamMemberID']> 0){
            $sendEmail = false;
            $status = "Not Sent (Already Assigned to Team Member '".$mail_data['ETATeamMemberID']."')";  
        }

        if($sendEmail){

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

        } else {
            $db->query('UPDATE EmailQueue SET Status = ? WHERE id = ?', $status, $mail_data['id']);
        }
    }

?>
