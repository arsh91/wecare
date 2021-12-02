<?php
include 'db_connection.php';

if(isset($_POST['TicketNum']) && isset($_POST['eta_radio']) && isset($_POST['teamMemberId'])){
			$teammemberid= $_POST['teamMemberId'];
            $ticketId = $_POST['TicketNum'];
			$eta_radio = $_POST['eta_radio'];
			
            

            $teamMemberName = $db->query('SELECT * FROM Team WHERE TeamMemberID = ?' , $teammemberid)->fetchArray();
           $assignedTeamMemberName= $teamMemberName['Fname']." ".$teamMemberName['Lname'];


            switch($eta_radio){
                
                case "resolve_2_hours":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = CURRENT_TIME + INTERVAL 2 HOUR , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_4_hours" :
                    
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = CURRENT_TIME + INTERVAL 4 HOUR , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?', $eta_radio, $teammemberid, $ticketId);
                    break;
                
                case "resolve_today_10am":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','10:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;
                
                case "resolve_today_12noon":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','12:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_today_2pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','14:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_today_6pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','18:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_today_9pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','21:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_tomorrow_10am":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE + INTERVAL 1 DAY , ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','10:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_tomorrow_12noon":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE + INTERVAL 1 DAY , ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','12:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_tomorrow_2pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE + INTERVAL 1 DAY , ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','14:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_tomorrow_6pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE + INTERVAL 1 DAY , ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','18:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;

                case "resolve_tomorrow_9pm":
                    $db->query('UPDATE MaintenanceTicket SET ETADate = CURRENT_DATE + INTERVAL 1 DAY , ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?','21:00:00', $eta_radio, $teammemberid, $ticketId);
                    break;
                
                case "resolve_nextturn":
                $checkoutdate = explode('-', $_POST['checkoutdate']);
                $newcheckoutdate = $checkoutdate[2].'-'.$checkoutdate[0].'-'.$checkoutdate[1];
                $db->query('UPDATE MaintenanceTicket SET ETADate = ?, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?', $newcheckoutdate, '16:00:00', $eta_radio, $teammemberid, $ticketId);
                break;

                case "custom_date_time":
                $customEtaDate= explode('-', $_POST['customEtaDate']);
                $newcustomEtaDate = $customEtaDate[2].'-'.$customEtaDate[0].'-'.$customEtaDate[1];
			    $newcustomEtaTime= $_POST['newcustomEtaTime'];
                    $db->query('UPDATE MaintenanceTicket SET ETADate = ?, ETATime = ? , ETA_radio=?, ETATeamMemberID=?  WHERE TicketNum=?', $newcustomEtaDate, $newcustomEtaTime, $eta_radio, $teammemberid, $ticketId);
                    break;

            }
            $ETA= $db->query('SELECT * FROM MaintenanceTicket WHERE TicketNum = ?' , $ticketId)->fetchArray();

            $etaDateTime = date("m-d-Y", strtotime($ETA['ETADate']) )." ".date("h:i A", strtotime($ETA['ETATime']) );

            $ticketData = $db->query('SELECT * FROM MaintenanceTicket WHERE TicketNum= ? AND ETATeamMemberID =?', $ticketId, $teammemberid)->fetchArray();
            $maintenancecategory = $ticketData['Issue'];

            $PropertyAddress = $db->query('SELECT * FROM Properties WHERE PropertyID= ?', $ticketData['property_Id'])->fetchArray();
            $doorCode = $PropertyAddress['DoorCode'];

                $from_email='toddknight@equisourceholdings.com';
                $phoneEmail = "1".$ETA['Phone']."@textmagic.com";
                $schedule_datetime = date('Y-m-d H:i:s');
                $bodytext = "<p>Hi ".$ETA['FirstName']." , Our team member, ".$assignedTeamMemberName." has responded to your request for assistance for Ticket Number ".$ticketId.", ".$maintenancecategory.".".$teamMemberName['Fname']." has stated that he expects to resolve this issue by ".date("m-d-Y", strtotime($ETA['ETADate']) )." at ".date("h:i A", strtotime($ETA['ETATime']) )." Please do not text back to this text message as this is an outgoing text number only. .</p>";

                $emailData = $db->query('INSERT into EmailQueue (FromEmail, Subject,  BodyText, ToEmail, TicketNum, TeamMemberID, Files, Status, ScheduleDate, noFlagEmails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',$from_email," ", $bodytext, $phoneEmail, $ticketId, $teammemberid," ", "Pending",$schedule_datetime, "1");

            echo json_encode(array("teamMemberName" => $assignedTeamMemberName, "etaDateTime" => $etaDateTime, "doorCode" => $doorCode ));
            exit;
        }
        
?>
