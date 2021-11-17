<?php
include 'db_connection.php';




if(isset($_POST['TicketNum'])  && isset($_POST['teamMemberId']) ){
    $ticketId = $_POST['TicketNum'];
    $teammemberid= $_POST['teamMemberId'];

    $feedbackData = $db->query('SELECT Issue , Phone, FirstName FROM MaintenanceTicket WHERE TicketNum = ?' , $ticketId)->fetchArray();
    //echo "<pre>"; print_r($feedbackData);
    $feedBackIssue = $feedbackData['Issue'];
    $feedBackPhone = $feedbackData['Phone'];
    $feedBackFirstName = $feedbackData['FirstName'];


    $from_email='toddknight@equisourceholdings.com';
    $phoneEmail = "1".$feedBackPhone."@textmagic.com";
    $schedule_datetime = date('Y-m-d H:i:s');

    $ticket_id = base64_encode($ticketId);
    $team_memberId= base64_encode($teammemberid);
    $feedbackLetterLink = "https://wecare.equisourceholdings.com/feedbackLetter.php?ticketNum=".$ticket_id."&teamMemberNo=".$team_memberId;
    // print_r($feedbackLetterLink);
    $bodytext = "<p>Hi ".$feedBackFirstName." , Please click here to let us know how we did on Ticket Number ".$ticketId.", ".$feedBackIssue.".".$feedbackLetterLink."</p>";

    $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject,  BodyText, ToEmail, TicketNum, TeamMemberID, Files, Status, ScheduleDate, noFlagEmails, Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)',$from_email," ", $bodytext, $phoneEmail, $ticketId, $teammemberid," ", "Pending",$schedule_datetime, "1", "request_feedBack_email");

    $updateFeedbackrequested = $db->query('UPDATE MaintenanceTicket SET Feedbackrequested =?  WHERE TicketNum=?', $schedule_datetime, $ticketId);
    $selectfeedbackData = $db->query('SELECT Feedbackrequested FROM MaintenanceTicket WHERE TicketNum = ?' , $ticketId)->fetchArray();

    $Feedbackrequested = date("m-d-Y h:i A", strtotime($selectfeedbackData['Feedbackrequested']) );

    //echo "<pre>"; print_r($emailData);
    echo json_encode(array("Feedbackrequested" => $Feedbackrequested));
    exit;
}
?>