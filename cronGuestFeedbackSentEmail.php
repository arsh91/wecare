<?php

include 'db_connection.php';
include 'ics.php';



$guestSendEmailData =$db->query('SELECT TicketDate, ical, TicketNum, ClosedBy, Issue, Phone, FirstName  FROM MaintenanceTicket AS MT LEFT JOIN Properties AS prop ON MT.property_Id = prop.PropertyID WHERE MT.ClosedDate IS NOT NULL AND MT.Feedbackrequested IS NULL LIMIT 0, 5')->fetchAll();
// echo "<pre>"; print_r($guestSendEmailData); echo "</pre>"; die();

foreach($guestSendEmailData as $mailData){

        $TicketDate = strtotime($mailData['TicketDate']);
        $file = $mailData['ical'];
        $obj = new ics();
        $icsEvents = $obj->getIcsEventsAsArray( $file );

            unset( $icsEvents [1] );
            $checkOutDate = "";
           
                
            foreach( $icsEvents as $icsEvent){
                $start = isset( $icsEvent ['DTSTART;VALUE=DATE'] ) ? $icsEvent ['DTSTART;VALUE=DATE'] : $icsEvent ['DTSTART'];
                $startDt = new DateTime ( $start );
                $startDate = $startDt->format ( 'Y-m-d' );
                $end = isset( $icsEvent ['DTEND;VALUE=DATE'] ) ? $icsEvent ['DTEND;VALUE=DATE'] : $icsEvent ['DTEND'];
                $endDt = new DateTime ( $end );
                $endDate = $endDt->format ( 'Y-m-d' );
                $eventName = $icsEvent['SUMMARY'];
                //echo "<pre>"; print_r($TicketDate);
               
                
                if(strtotime($startDate) <= $TicketDate && strtotime($endDate) >= $TicketDate){
                    
                    $feedBackTicketNum = $mailData['TicketNum'];
                    $feedBackTeamMemberId = $mailData['ClosedBy'];
                    $feedBackIssue = $mailData['Issue'];
                    $feedBackPhone = $mailData['Phone'];
                    $feedBackFirstName = $mailData['FirstName'];
                    $from_email='toddknight@equisourceholdings.com';
                    $phoneEmail = "1".$feedBackPhone."@textmagic.com";
                    $schedule_datetime = date('Y-m-d H:i:s');

                    $ticket_id = base64_encode($feedBackTicketNum);
                    $team_memberId= base64_encode($feedBackTeamMemberId);
                    $feedbackLetterLink = "https://wecare.equisourceholdings.com/feedbackLetter.php?ticketNum=".$ticket_id."&teamMemberNo=".$team_memberId;
                   print_r($feedbackLetterLink);
                    $bodytext = "<p>Hi ".$feedBackFirstName." , Please click here to let us know how we did on Ticket Number ".$feedBackTicketNum.", ".$feedBackIssue.". ".$feedbackLetterLink."</p>";
                    print_r($bodytext);
                  
                    
                     $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject,  BodyText, ToEmail, TicketNum, TeamMemberID, Files, Status, ScheduleDate, noFlagEmails, Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)',$from_email," ", $bodytext, $phoneEmail, $feedBackTicketNum, $feedBackTeamMemberId," ", "Pending",$schedule_datetime, "1", "request_feedBack_email");

                     $updateFeedbackrequested = $db->query('UPDATE MaintenanceTicket SET Feedbackrequested =?  WHERE TicketNum=?', $schedule_datetime, $feedBackTicketNum);

                     break;
                }
                
        }
       


     
        
    
}




?>
