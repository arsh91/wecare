<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	include 'db_connection.php';
    include 'ics.php';
?>
<!DOCTYPE html>
<html>

<head>
<title>Ticket Detail</title>
<?php if(isset($_GET['ticketNum']) && isset($_GET['teamMemberNo']) ){  ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/jquery-ui-datepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php } ?>
</head>
<style>

.queue_table {
    max-height: 600px;
    max-width: 1070px;
    overflow: hidden;
    overflow-y: scroll;
    overflow-x: scroll;
    margin-bottom: 15px;
}
.emailqueue_table thead tr th {
    border: 2px solid #f2f2f2;
}

.morecontent span {
  display:none;
}
.morelink {
  display:block;
}


</style>
<body>
    <?php 
$close_date = date("Y-m-d");
$close_time = date("H:i:s");
if(isset($_GET['ticketNum']) && isset($_GET['teamMemberNo']) ){
$ticketNumber = base64_decode($_GET['ticketNum']);
$teamMemberNo = base64_decode($_GET['teamMemberNo']);
}
else{
    $ticketNumber = $_POST['ticketNum'];
$teamMemberNo = $_POST['teamMembersNo'];
}

// UPDATE THE FIELDS AFTER TICKET CLOSED
if(isset($_POST['closeinput']) && $_POST['closeinput'] == "1"){
    
    $notes = $_POST['notes'];
    $hoursbilled = $_POST['hoursbilled'];
    $GuestSatisfactionLevel = $_POST['Guest_Satisfaction_Level_radio'];
    $closeTicketData = $db->query('UPDATE MaintenanceTicket SET Notes = ?, ClosedDate = ?, ClosedTime= ?, ClosedBy= ?, Hoursbilled=?, Guestsatisfactionlevel=? WHERE TicketNum= ?', $notes, $close_date, $close_time, $teamMemberNo, $hoursbilled,  $GuestSatisfactionLevel, $ticketNumber);
}

$current_date = date("Y-m-d H:i:s");
$NotificationData = $db->query('SELECT * FROM EmailQueue WHERE TicketNum = ? AND TeamMemberID= ?',$ticketNumber, $teamMemberNo)->fetchArray();

// IF THE LINK IS CLICKED IT WILL UPDATE THE VALUE
if($NotificationData['NotificationRead'] == NULL && (isset($_GET['phone']) && $_GET['phone'] == "1")){
   
    $ticketData = $db->query(' UPDATE EmailQueue SET NotificationRead = ? WHERE ToEmail LIKE "%textmagic.com%" AND TicketNum= ? AND TeamMemberID= ?', $current_date, $ticketNumber, $teamMemberNo);
}else{
    $ticketData = $db->query(' UPDATE EmailQueue SET NotificationRead = ? WHERE ToEmail NOT LIKE "%textmagic.com%" AND TicketNum= ? AND TeamMemberID= ?', $current_date, $ticketNumber, $teamMemberNo);
}

$ticketData = $db->query('SELECT * FROM MaintenanceTicket WHERE TicketNum= ?', $ticketNumber)->fetchArray();

$PropertyAddress = $db->query('SELECT * FROM Properties WHERE PropertyID= ?', $ticketData['property_Id'])->fetchArray();
$address = $PropertyAddress['Address'] .', '. $PropertyAddress['City'] . ', '. $PropertyAddress['State'] . ', '. $PropertyAddress['Zip'];

// GET THE MEMBER DATA NEED TO PRINT IN TICKET AFTER CLOSED
if($ticketData['ClosedBy']){
$teamMemberData = $db->query('SELECT * FROM Team WHERE TeamMemberID = ?', $ticketData['ClosedBy'])->fetchArray();
}

if($ticketData['ETATeamMemberID'] > 0){
$teamMemberName = $db->query('SELECT * FROM Team WHERE TeamMemberID = ?' , $ticketData['ETATeamMemberID'])->fetchArray();
}

$teamAdminData = $db->query('SELECT admin FROM Team WHERE TeamMemberID = ?' ,$teamMemberNo)->fetchArray();

//QUERY TO GET TECHNOTES FROM MAINTENENCEASSIGNMENTS
$technotes = $db->query('SELECT technotes FROM MaintenanceAssignements WHERE CategoryID = ? AND PropertyID=?', $ticketData['Category_Id'], $ticketData['property_Id'])->fetchArray();
// echo "<pre>"; print_r($technotes);

$eta_custom_date_time = "display:none;";
$eta_custom_date = $eta_custom_time = '';
		
?>
    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" id="notesform">
                    <div class="modal-header">
                        <h5 class="modal-title" id="submitModalLabel">NOTES</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label"><strong>Notes:</strong></label>
                            <textarea class="form-control" name="notes" id="notes" placeholder="Enter your notes"
                                required></textarea>
                            <div class="notes_text text-right"><span class="notesLength" id="notes_length">250</span>
                                characters
                                remaining </div>
                            <input type="hidden" value="1" name="closeinput">
                        </div>
                        <div class="form-group">
                            <label for="Hours-Billed" class="col-form-label"><strong>Hours Billed for Job:</strong></label>
                            <input class="form-control"  type="number" id="hoursbilled" name="hoursbilled">
                        </div>
                        <div class="form-group">
                            <label for="Guest-Satisfaction-Level" class="col-form-label"><strong>Guest Satisfaction Level:</strong></label>
                            <div class="form-check">
                                <input
                                class="form-check-input" type="radio" value="Guest_appeared_satisfied"
                                name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio1">
                                <label class="form-check-label" for="Guest_Satisfaction_Level_radio1">The Guest appeared satisfied
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                class="form-check-input" type="radio" value="Guest_did_not_appear_satisfied_dissatisfied"
                                name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio2">
                                <label class="form-check-label" for="Guest_Satisfaction_Level_radio2">The Guest did not appear either satisfied or dissatisfied
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                class="form-check-input" type="radio" value=" Guest_dissatisfied_with_resolution_other issues"
                                name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio3">
                                <label class="form-check-label" for="Guest_Satisfaction_Level_radio3">The Guest was dissatisfied with this resolution and/or other issues
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                class="form-check-input" type="radio" value="not_certain"
                                name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio4">
                                <label class="form-check-label" for="Guest_Satisfaction_Level_radio4">I’m not certain of the Guest’s satisfaction level
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="insertnotes" id="submitnotes"
                            class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="ticket_datils">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card_wrapper ">
        <?php if(isset($_GET['ticketNum']) && isset($_GET['teamMemberNo']) ){ ?>
                        <div class="brand text-center mb-4">
                            <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                        </div>
                        <div class="card col-md-12 m-auto p-0 ticketDetailCard">
                            <div class="card-header text-center">
                                <div class="row">
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-3  p-2">
                                        Ticket Details
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group m-auto">
                                            <a href="http://vacationrentals.equisourceholdings.com/maintainence_tickets.php"
                                                target="_blank"><button type="button" name="viewticket" id="viewticket"
                                                    class="btn btn-primary">View Maintenance Log</button></a>
                                        </div>
                                    </div>
                                    <?php if($teamAdminData['admin'] == 'Y') {?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           <button type="button" name="emailQueueData" id="emailQueueData"
                                                    class="btn btn-primary"> Notifications</button>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                                    <div class="card-body  m-auto cardDetails">
                                        <?php } ?>
                                        <p><span class="titleStyle"> Property Name: </span><?= $ticketData['Property']; ?> </p>
                                        <p><span class="titleStyle"> Urgency: </span><?= $ticketData['Urgency']; ?> </p>
                                        <p><span class="titleStyle"> Issue: </span><?= $ticketData['Issue']; ?> </p>
                                        <p><span class="titleStyle"> Issue Description:
                                            </span><?= $ticketData['IssueDescription']; ?>
                                        </p>
                                        <p><span class="titleStyle">First Name: </span><?= $ticketData['FirstName']; ?> </p>
                                        <p><span class="titleStyle"> Phone: </span><?= $ticketData['Phone']; ?> </p>
                                        <p><span class="titleStyle"> Ticket Date:
                                            </span><?= date("m-d-Y", strtotime($ticketData['TicketDate']) ); ?> </p>
                                        <p><span class="titleStyle"> Ticket Time:
                                            </span><?= date("h:i A", strtotime($ticketData['TicketTime']) ); ?> </p>
                                        <p><span class="titleStyle"> Ticket Number:
                                            </span><span class="ticket_id"><?= $ticketData['TicketNum']; ?> </span></p>
                                        <p><span class="titleStyle"> Address:
                                            </span>
                                            <a target="_blank" href="https://www.google.com/maps/place/<?php echo
                                                str_replace(' ', '+', $address);?>">
                                                <?= $address;
                                            ?> </a>
                                        </p>
                                        <p><span class="titleStyle"> Gate code:
                                            </span><?= $PropertyAddress['GateCode']; ?>
                                        </p>

                                        <p class="doorcode">
                                        </p>
                                        <?php
                                        if($ticketData['ETATeamMemberID'] > 0 && $teamMemberName['ReleaseDoorCode'] == "Y"){
                                            ?>
                                        <p><span class="titleStyle"> Door code:
                                            </span><?= $PropertyAddress['DoorCode']; ?>
                                        </p>

                                        <?php
                                        }
                                        $file = $PropertyAddress['ical'];
                                        $obj = new ics();
                                        $icsEvents = $obj->getIcsEventsAsArray( $file );
                                        
                                            unset( $icsEvents [1] );
                                            $checkOutDate = "";
                                            $table_html = '<table class="table table-bordered table-striped"><thead><tr><th> Event </th><th> Check In </th><th> Check Out </th></tr></thead><tbody>';
                                            //echo "<pre>"; print_r($icsEvents);//die();
                                         
                                            date_default_timezone_set("America/Chicago");
                                            $current_time = date("H");
                                            $current_date = date("m/d/Y");
                                             $nextFlag = false;
                                            foreach( $icsEvents as $icsEvent){
                                                $start = isset( $icsEvent ['DTSTART;VALUE=DATE'] ) ? $icsEvent ['DTSTART;VALUE=DATE'] : $icsEvent ['DTSTART'];
                                                $startDt = new DateTime ( $start );
                                                $startDate = $startDt->format ( 'm/d/Y' );
                                                $end = isset( $icsEvent ['DTEND;VALUE=DATE'] ) ? $icsEvent ['DTEND;VALUE=DATE'] : $icsEvent ['DTEND'];
                                                $endDt = new DateTime ( $end );
                                                $endDate = $endDt->format ( 'm/d/Y' );
                                                $eventName = $icsEvent['SUMMARY'];
                                                $table_html .= '<tr><td>'.$eventName.'</td><td>'.date('Y-m-d',strtotime($startDate)).'</td><td>'.date('Y-m-d',strtotime($endDate)).'</td></tr>';
                                                if( $nextFlag){
                                                    $checkOutDate = date("m-d-Y", strtotime($endDate));
                                                }
                                                if(strtotime($endDate) == strtotime($current_date)){
                                                    if( $current_time < 14){
                                                        $checkOutDate = date("m-d-Y", strtotime($endDate));
                                                    }else if($current_time >= 14){
                                                        $nextFlag = true;
                                                    }
                                                } else if($checkOutDate == "" && (strtotime($endDate) > strtotime($current_date))) {
                                                    $checkOutDate = date("m-d-Y", strtotime($endDate));
                                                } else {
                                                    $nextFlag = false;
                                                }
                                        }
                                        $table_html.='</tbody></table>';
                                        ?>
                                        <p><span class="titleStyle"> Next Check Out Date: </span><span class="nextCheckoutDateValue"> <?php 
                                        if(!empty($checkOutDate)){
                                            echo $checkOutDate;
                                        }
                                        ?>
                                            </span>
                                        </p>

                                        <p><span class="titleStyle"> Calendar:
                                            </span><span><a data-toggle="collapse" href="#collapse_reservedTable" role="button" aria-expanded="false" aria-controls="collapseExample">Click here</a></span>
                                        </p>
                                        <div class="collapse" id="collapse_reservedTable">
                                            <div class="reserved_table">
                                            <?php echo $table_html; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if($ticketData['ClosedDate'] != "" || $ticketData['ClosedDate'] != NULL) { ?>
                                        <p><span class="titleStyle"> Ticket Closed Date:
                                            </span><?= $ticketData['ClosedDate']; ?> </p>
                                        <p><span class="titleStyle"> Ticket Closed Time:
                                            </span><?= $ticketData['ClosedTime']; ?> </p>
                                        <p><span class="titleStyle"> Ticket Closed By:
                                            </span><?= $teamMemberData['Fname']." ". $teamMemberData['Lname']; ?> </p>
                                        <p><span class="titleStyle"> Notes:
                                            </span><?= $ticketData['Notes']; ?> </p>
                                        <?php } if($ticketData['ETATeamMemberID'] == 0) { ?>
                                        <div>
                                            <p><span class="titleStyle">Assigned to:</span><span class="assignedTo">
                                                    Unassigned
                                                    Ticket
                                                </span></p>
                                            <p><span class="titleStyle">ETA:</span><span class="etaDateTime"> Unassigned
                                                    Ticket
                                                </span></p>
                                        </div>
                                        <div class="eta-radio-container mb-3" id="eta_container">
                                            <div class="form-check">
                                                <input class="teammember" type="hidden"
                                                    teamMemberId=<?php echo $teamMemberNo;?>>
                                                <input class="form-check-input radiobutton" type="radio" eta="today" etatime ="2_hours" value="resolve_2_hours"
                                                    name="eta_radio" id="resolve_2_hours"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_2_hours">
                                                    I can resolve within 2 hours
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="today" etatime="4_hours"value="resolve_4_hours"
                                                    name="eta_radio" id="resolve_4_hours"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_4_hours">
                                                    I can resolve within 4 hours
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobuttons" type="radio" eta="today" DisabledEtaTime= "10" etatime="10:00 AM" value="resolve_today_10am"
                                                    name="eta_radio" id="resolve_today_10am"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_today_10am">
                                                    I can resolve today by 10 AM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobuttons" type="radio" eta="today" DisabledEtaTime= "12" etatime="12:00 PM" value="resolve_today_12noon"
                                                    name="eta_radio" id="resolve_today_12noon"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_today_12noon">
                                                I can resolve today by 12 NOON
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobuttons" type="radio" eta="today" DisabledEtaTime= "14" etatime="02:00 PM"
                                                    value="resolve_today_2pm" name="eta_radio" id="resolve_today_2pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_today_2pm">
                                                I can resolve today by 2 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobuttons" type="radio" eta="today" DisabledEtaTime= "18" etatime="06:00 PM"
                                                    value="resolve_today_6pm" name="eta_radio" id="resolve_today_6pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_today_6pm">
                                                I can resolve today by 6 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobuttons" type="radio" eta="today" DisabledEtaTime= "21"
                                                etatime="09:00 PM"
                                                value="resolve_today_9pm" name="eta_radio" id="resolve_today_9pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_today_9pm">
                                                I can resolve today by 9 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="tomorrow"  etatime="10:00 AM" value="resolve_tomorrow_10am" name="eta_radio" id="resolve_tomorrow_10am"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_tomorrow_10am">
                                                I can resolve tomorrow by 10 AM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="tomorrow" etatime="12:00 PM"
                                                    value="resolve_tomorrow_12noon" name="eta_radio" id="resolve_tomorrow_12noon"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_tomorrow_12noon">
                                                I can resolve tomorrow by 12 NOON
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="tomorrow" etatime="02:00 PM"
                                                    value="resolve_tomorrow_2pm" name="eta_radio" id="resolve_tomorrow_2pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_tomorrow_2pm">
                                                I can resolve tomorrow by 2 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="tomorrow" etatime="06:00 PM"
                                                    value="resolve_tomorrow_6pm" name="eta_radio" id="resolve_tomorrow_6pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_tomorrow_6pm">
                                                I can resolve tomorrow by 6 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="tomorrow" etatime="09:00 PM"
                                                    value="resolve_tomorrow_9pm" name="eta_radio" id="resolve_tomorrow_9pm"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_tomorrow_9pm">
                                                I can resolve tomorrow by 9 PM
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton" type="radio" eta="nextTurn" etatime="04:00 PM"
                                                    value="resolve_nextturn" name="eta_radio" id="resolve_nextturn"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="resolve_nextturn">
                                                    I can resolve at next turn
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton unable_resolve_radio" type="radio"
                                                    value="unable_resolve" name="unable_resolve_eta_radio" id="unable_resolve"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="unable_resolve">
                                                    Unable/unwilling to resolve
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input radiobutton custom_date_time" type="radio"
                                                    value="custom_date_time" name="custom_eta_radio" id="custom_date_time"
                                                    data-id=<?php echo $ticketData['TicketNum']; ?>>
                                                <label class="form-check-label" for="custom_date_time">
                                                    Custom date and time
                                                </label>
                                            </div>
                                        </div>
                                        <!-- CUSTOM ETA CODE -->
                                        <div class="date-range-form form-group custom_field" style="<?php echo $eta_custom_date_time; ?>">
                                            <div class="form-group">
                                                <div class="row" >
                                                    <div class="col">
                                                        <input type="text" class="form-control" placeholder="ETA Date" id="eta_custom_date"
                                                            name="eta_custom_date" value="<?= $eta_custom_date ?>">
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" placeholder="ETA Time" id="eta_custom_time"
                                                            name="eta_custom_time" value="<?= $eta_custom_time ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <button type="submit" teamMemberId=<?php echo $teamMemberNo;?> id="customDatetime" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="assigned_msg">

                                        <?php } else{?>
                                        <div>
                                            <p><span class="titleStyle">Assigned to:</span>
                                                <span><?php echo $teamMemberName['Fname']." ". $teamMemberName['Lname']; ?>
                                                </span>
                                            </p>
                                            <p><span class="titleStyle">ETA:</span>
                                                <span><?php echo date("m-d-Y", strtotime($ticketData['ETADate']) )." ".date("h:i A", strtotime($ticketData['ETATime']) ); ?></span>
                                            </p>

                                            <?php }?>
                                        </div>
                                        <!-- TECH NOTES FIELD -->
                                        <p><span class="titleStyle ">Tech Notes: </span><span class="more"><?= $technotes["technotes"]; ?> </span></p>
                                        
                                        <!-- MAINTENENCE HISTORY TOOGLE TABLE  -->
                                        <?php
                                         $toogleMaintenenceDatas = $db->query('SELECT TicketDate , Issue , IssueDescription FROM MaintenanceTicket WHERE Property = ? ORDER BY TicketDate DESC' ,$ticketData['Property'])->fetchAll();

                                         
                                          $MaintenenceTable_html = 
                                          '<div class="form-group">
                                                <label for="Guest-Satisfaction-Level" class="col-form-label"><strong>Guest Satisfaction Level:</strong></label>
                                                <div class="form-check">
                                                    <input
                                                    class="form-check-input" type="radio" value="Guest_appeared_satisfied"
                                                    name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio1">
                                                    <label class="form-check-label" for="Guest_Satisfaction_Level_radio1">The Guest appeared satisfied
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input
                                                    class="form-check-input" type="radio" value="Guest_did_not_appear_satisfied_dissatisfied"
                                                    name="Guest_Satisfaction_Level_radio" id="Guest_Satisfaction_Level_radio2">
                                                    <label class="form-check-label" for="Guest_Satisfaction_Level_radio2">The Guest did not appear either satisfied or dissatisfied
                                                    </label>
                                                </div>
                                            </div> <div class="reserved_table">
                                          <table class="table table-bordered table-striped"><thead style="position: sticky;top:0; background:#dee2e6;"><tr>
                                            
                                          </tr><tr><th> Date</th><th>Issue</th><th>IssueDescription</th></tr></thead><tbody>';
  
                                          foreach($toogleMaintenenceDatas as $toogleMaintenenceData){
                                              //echo "<pre>"; print_r($toogleTeamData);
  
                                          $MaintenenceTable_html .= '<tr><td>'.$toogleMaintenenceData['TicketDate'].'</td><td>'.$toogleMaintenenceData['Issue'].'</td><td>'.$toogleMaintenenceData['IssueDescription'].'</td></tr>';
                                          }
                                          $MaintenenceTable_html.='</tbody></table>  </div>';
                                          
                                        ?>
                                        
                                        <!-- PROPERTY NAME MAINTENENCE HISTORY LINK -->
                                        <p><span class="titleStyle"> <?= $ticketData['Property']; ?> Maintenance History:
                                            </span><span><a data-toggle="collapse" href="#collapse_maintenance_history" role="button" aria-expanded="false" aria-controls="collapseExample">Click here</a></span>
                                        </p>
                                        <div class="collapse" id="collapse_maintenance_history">
                                           
                                            <?php echo $MaintenenceTable_html; ?>
                                          
                                        </div>


                                            <?php if($teamAdminData['admin'] == 'Y' && $ticketData['Feedbackrequested'] == NULL && $ticketData['ClosedDate'] != NULL ){?>
                                            <div class="form-group pt-2 requestFeedbackBtn ">
                                                    <button type="submit" teamMemberId=<?php echo $teamMemberNo;?> name="requestFeedbackBtn" id="requestFeedbackBtn" value="requestFeedbackBtn" class="btn btn-primary requestFeedback">Request Feedback</button>
                                            </div>
                                                <?php } elseif($teamAdminData['admin'] == 'Y' && $ticketData['Feedbackrequested'] != NULL && $ticketData['ClosedDate'] != NULL) { ?>
                                                <div class='form-group pt-2'>
                                                    <p><span class="titleStyle">Feedback Requested :</span>
                                                    <span><?php echo date("m-d-Y h:i A", strtotime($ticketData['Feedbackrequested']) ); ?>
                                                    </span>
                                                </p>
                                                </div>
                                                <?php } ?>
                                                <div class='form-group feedbackDateTime pt-2' style="display:none;">
                                                    <p><span class="titleStyle">Feedback Requested :</span>
                                                    <span class="showFeedbackData"></span>
                                                </p>
                                                </div>
                                                <div class="assigned_feedback"></div>
                                            
                                            <div id="assigned_membername"></div>
                                            <?php if(isset($_GET['ticketNum']) && isset($_GET['teamMemberNo']) ){ ?>
                                   </div>
                            
                                    <div class="card-footer"> 
                                        <div class="row"> 
                                            <div class="col-md-4">
                                            </div>     
                                            <div class="form-group  text-center col-md-4 ">
                                                <?php   if($ticketData['ClosedDate'] == "" || $ticketData['ClosedDate'] == NULL ) { ?>         
                                                <button type="button" name="CloseTicket" id="ticketClose"
                                                    class="btn btn-primary closedticket mr-2">Close Ticket</button>
                                            <?php } ?>
                                                <button type="button" name="ClosePage" id="closePage"
                                                    class="btn btn-primary closepage">Close Page</button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            </div>  
                                        
                                    </div>
                            
                        </div>
                        <?php } ?>
                        <div class="container ticketDetailPics">
                            <div class="row">
                                <?php if($ticketData['Pic1'] != "" ){?>
                                <div class="card col-md-3 mt-2 p-0 ">
                                    <div class="card-body text-center">
                                        <img src="<?= $ticketData['Pic1']; ?>" alt="" width="200px">
                                    </div>
                                </div>
                                <?php } if($ticketData['Pic2'] != "" ){ ?>
                                <div class="card col-md-3 mt-2 p-0">
                                    <div class="card-body text-center">
                                        <img src="<?= $ticketData['Pic2']; ?>" alt="" width="200px">
                                    </div>
                                </div>
                                <?php } if($ticketData['Pic3'] != "" ){ ?>
                                <div class="card col-md-3 mt-2 p-0">
                                    <div class="card-body text-center">
                                        <img src="<?= $ticketData['Pic3']; ?>" alt="" width="200px">
                                    </div>
                                </div>
                                <?php } if($ticketData['Pic4'] != "" ){ ?>
                                <div class="card col-md-3 mt-2 p-0">
                                    <div class="card-body text-center">
                                        <img src="<?= $ticketData['Pic4']; ?>" alt="" width="200px">
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                                        <?php
                                        if($teamAdminData['admin'] == 'Y'){
                                        // GET TEAM DATA FOR TOOGLE TABLE
                                        $toogleTeamDatas = $db->query('SELECT BodyText , ToEmail , TeamMemberId , Status , ScheduleDate , TImeDateSent , NotificationRead FROM EmailQueue WHERE TicketNum = ?' ,$ticketNumber)->fetchAll();

                                       //EMAIL QUEUE TOOGLE TABLE 
                                        $emailQueueTable_html = '<table class="table table-bordered table-striped emailqueue_table"><thead style="position: sticky;top:0; background:#dee2e6;"><tr><th> BodyText</th><th>ToEmail</th><th>TeamMemberId</th><th>Status</th><th>ScheduleDate</th><th>  TImeDateSent</th><th>NotificationRead</th></tr></thead><tbody>';

                                        foreach($toogleTeamDatas as $toogleTeamData){
                                            //echo "<pre>"; print_r($toogleTeamData);

                                        $emailQueueTable_html .= '<tr><td>'.$toogleTeamData['BodyText'].'</td><td>'.$toogleTeamData['ToEmail'].'</td><td>'.$toogleTeamData['TeamMemberId'].'</td><td>'.$toogleTeamData['Status'].'</td><td>'.$toogleTeamData['ScheduleDate'].'</td><td>'.$toogleTeamData['TImeDateSent'].'</td><td>'.$toogleTeamData['NotificationRead'].'</td></tr>';
                                        }
                                        $emailQueueTable_html.='</tbody></table>';
                                        
                                        ?>
                                        
                                        <div class="card col-md-12 m-auto p-0 emailQueue_table" style="display:none;">
                                            <div class="card-header ">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                    <button type="button" name="emailQueueBackBtn" id="emailQueueBackBtn"
                                                    class="btn btn-primary text-left">Back</button>
                                                    </div>
                                                    <div class="col-md-4 ml-6">
                                                    <strong>Emailqueue data table</strong>
                                                    </div>
                                                    <div class="col-md-4">
                                                   
                                                    </div>
                                               
                                                 
                                                </div>
                                            
                                            </div>
                                            <div class="card-body  m-auto">
                                                <div class= "queue_table">
                                                <?php echo $emailQueueTable_html; ?>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center"> 
                                            
                                            </div>
                                           
                                        </div>        
                                        
                                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php  if(isset($_GET['ticketNum']) && isset($_GET['teamMemberNo']) ){ ?>
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/custom.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/jquery-ui-datepicker.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<?php } ?>
    <script type="text/javascript">
    $(document).ready(function() {
        //ETA RADIO BUTTONS DISABLED FUCTION
        $( ".radiobuttons" ).each(function() {
            var DisabledEtaTime=$(this).attr("DisabledEtaTime");
            var eta =$(this).attr("eta");
            var today = new Date();
            var Time = today.getHours();
            if(DisabledEtaTime <= Time && eta == "today" ){
                $(this).attr('disabled', 'disabled');  
            }
        });
        var maxchars = 250;
        $('textarea').keyup(function() {
            var tlength = $(this).val().length;
            $(this).val($(this).val().substring(0, maxchars));
            var tlength = $(this).val().length;
            remain = maxchars - parseInt(tlength);
            $('#notes_length').text(remain);
        });

        $('input[name=custom_eta_radio]').change(function() {
            $("input[type=radio][name=eta_radio]").prop('checked', false);
            $("input[type=radio][name=unable_resolve_eta_radio]").prop('checked', false);
            if ($(this).val() == 'custom_date_time') {
                $('.custom_field').show();
                $('input#eta_custom_date').attr('required', 'required');
                $('input#eta_custom_time').attr('required', 'required');
            }
        });

        var dateFormat = "mm-dd-yy",
        
            from = $("#eta_custom_date").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: dateFormat
            
            });
            to = $('#eta_custom_time').timepicker({
                timeFormat: 'h:mm p',
                interval: 60,
                // minTime: '10',
                // maxTime: '6:00pm',
                defaultTime: '',
                // startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }
            return date;
        }

        $('input[name=unable_resolve_eta_radio]').click(function() {
            $("input[type=radio][name=eta_radio]").prop('checked', false);
            $("input[type=radio][name=custom_eta_radio]").prop('checked', false);
            $('.custom_field').hide();
            $('input#eta_custom_date').removeAttr('required');
            $('input#eta_custom_time').removeAttr('required');
        });

        //ETA functionality code Fuction
        function ETAFunction(ticket_id,eta_radio,teamMemberId,checkoutdate="",customEtaDate="",newcustomEtaTime="") {
             $.ajax({
                type: "POST",
                url: "etaupdate.php",
                data: {
                    'TicketNum': ticket_id,
                    'eta_radio': eta_radio,
                    'teamMemberId': teamMemberId,
                    'checkoutdate': checkoutdate,
                    'customEtaDate': customEtaDate,
                    'newcustomEtaTime': newcustomEtaTime
                },
                success: function(response) {
                    var response = JSON.parse(response);
                    if (response.doorCode) {
                        $(".doorcode").html("<span class='titleStyle'>" + " Door code:" +
                            "</span>" + " " + response.doorCode);
                    }

                    if (response.teamMemberName) {
                        $('.assignedTo').html(" " + response.teamMemberName);
                    }

                    if (response.etaDateTime) {
                        $('.etaDateTime').html(" " + response.etaDateTime);
                    }

                    $('.assigned_msg').append(
                        '<div class="alert alert-success mt-3 eta_success_msg" role="alert">Ticket ETA Updated Successfully!</div>'
                    );
                    setTimeout(() => {
                        $('.eta_success_msg').fadeOut('slow');
                    }, 2000);

                    $('#eta_container').hide();


                    


                }
            });
        };
        $('input[name=eta_radio]').click(function() {
            $("input[type=radio][name=unable_resolve_eta_radio]").prop('checked', false);
            $("input[type=radio][name=custom_eta_radio]").prop('checked', false);
            $('.custom_field').hide();
            $('input#eta_custom_date').removeAttr('required');
            $('input#eta_custom_time').removeAttr('required');
            var eta =$(this).attr("eta");
            var etatime=$(this).attr("etatime");
            var checkoutdate = $.trim($('.nextCheckoutDateValue').text());
            if(eta =="today"){

            if(etatime =="2_hours"){

            var today = new Date();
            var date = (today.getMonth()+1)+'-'+today.getDate()+'-'+today.getFullYear();
            var Time = (today.getHours()+2) + ":" + today.getMinutes() + ":" + today.getSeconds();
            var time = moment(Time, "HH:mm:ss").format("hh:mm A");
            var dateTime = date+' '+time;
            }
            else if( etatime =="4_hours"){
               
            var today = new Date();
            var date = (today.getMonth()+1)+'-'+today.getDate()+'-'+today.getFullYear();
            var Time = (today.getHours()+4) + ":" + today.getMinutes() + ":" + today.getSeconds();
            var time = moment(Time, "HH:mm:ss").format("hh:mm A");
            var dateTime = date+' '+time;
            }  
            else{             
               var today = new Date();
               var date = (today.getMonth()+1)+'-'+today.getDate()+'-'+today.getFullYear();
               var time= $(this).attr("etatime");
               var dateTime = date+' '+time;
               }
            }
            if(eta =="tomorrow"){
               
            var today = new Date();
            var date = (today.getMonth()+1)+'-'+(today.getDate()+1)+'-'+today.getFullYear();
            var time= $(this).attr("etatime");
            var dateTime = date+' '+time;
            }
            if(eta == "nextTurn"){
                var date = checkoutdate;
                var time=$(this).attr("etatime");
                var dateTime = date+" "+time;
               
            }
            
            
            
          
            if (confirm("Please confirm.  You wish to set an ETA of"+" "+dateTime+" "+"for this ticket?")) {
            var ticket_id = $('.ticket_id').text();
            var teamMemberId = $('.teammember').attr('teamMemberId');
            var eta_radio = $(this).val();

            ETAFunction(ticket_id,eta_radio,teamMemberId,checkoutdate,"","");
                    
                }
            
        });
        $('#customDatetime').click(function() {
            var customEtaDate = $('#eta_custom_date').val();
            //var newcustomEtaDate = moment(customEtaDate,"YYYY-DD-MM").format("MM-DD-YYYY");
            var customEtaTime = $('#eta_custom_time').val();
            var dateTime = customEtaDate+" "+customEtaTime;

            if (confirm("Please confirm.  You wish to set an ETA of"+" "+dateTime+" "+"for this ticket?")) {

            var ticket_id = $('.ticket_id').text();
            var teamMemberId = $(this).attr('teamMemberId');
            var eta_radio = $(".custom_date_time").val();
            var newcustomEtaTime = moment(customEtaTime, "hh:mm A").format("HH:mm:ss");

            ETAFunction(ticket_id,eta_radio,teamMemberId,"",customEtaDate,newcustomEtaTime)
            $('.custom_field').hide();
            $('input#eta_custom_date').removeAttr('required');
            $('input#eta_custom_time').removeAttr('required');
           
            }
        });

        $('.requestFeedback').click(function() {
            
            var ticket_id = $('.ticket_id').text();
            var teamMemberId = $('.requestFeedback').attr('teamMemberId');
            

            $.ajax({
                type: "POST",
                url: "requestFeedbackEmail.php",
                data: {
                    'TicketNum': ticket_id,
                    'teamMemberId': teamMemberId
                     },
                     success: function(response) {
                        
                        var response = JSON.parse(response);
                        if (response.Feedbackrequested) {
                        
                        $('.requestFeedbackBtn').hide();
                        $('.feedbackDateTime').show();

                        $('.showFeedbackData').html(" " +response.Feedbackrequested);
                    }
                    
                    $('.assigned_feedback').append(
                        '<div class="alert alert-success mt-3 feedback_success_msg" role="alert"> Feedback requested Successfully!</div>'
                    );
                    setTimeout(() => {
                        $('.feedback_success_msg').fadeOut('slow');
                    }, 2000);

                     }
            });


        });
        $('#emailQueueData').click(function() {
            $('.ticketDetailCard').hide();
            $('.ticketDetailPics').hide();
            $('.emailQueue_table').show();
            
        });

        $('#emailQueueBackBtn').click(function() {
            $('.emailQueue_table').hide();
            $('.ticketDetailCard').show();
            $('.ticketDetailPics').show();

            


        });
        
        //Read More/Less Content for TechNotes
        var showChar = 100;
        var ellipsestext ="...";
        var moretext ="Show more";
        var lesstext ="Show less";


        $('.more').each(function() {
            var content = $(this).html();

            if(content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
             }

        });

        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

    });

    $('.closepage').click(function() {      
                close();
           
       });
    </script>
</body>

</html>
