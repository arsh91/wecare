<?php
include 'db_connection.php';

$ticketNumber = base64_decode($_GET['ticketNum']);
$teamMemberNo = base64_decode($_GET['teamMemberNo']);

$maintenanceData = $db->query('SELECT * FROM MaintenanceTicket WHERE TicketNum= ? AND GuestRating IS NULL', $ticketNumber)->fetchArray();
if(!empty($maintenanceData['ETATeamMemberID'])){
    $teamData = $db->query('SELECT Fname,Lname FROM Team WHERE TeamMemberID= ?', $maintenanceData['ETATeamMemberID'])->fetchArray();
     $teamMemberName = $teamData['Fname']." ".$teamData['Lname'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Feedback Form </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
#full-stars-example-two {
}
 #full-stars-example-two .rating-group {
	 display: inline-flex;
}
 #full-stars-example-two .rating__icon {
	 pointer-events: none;
}
 #full-stars-example-two .rating__input {
	 position: absolute !important;
	 left: -9999px !important;
}
 #full-stars-example-two .rating__input--none {
	 display: none;
}
 #full-stars-example-two .rating__label {
	 cursor: pointer;
	 padding: 0 0.1em;
	 font-size: 2rem;
}
 #full-stars-example-two .rating__icon--star {
	 color: orange;
}
 #full-stars-example-two .rating__input:checked ~ .rating__label .rating__icon--star {
	 color: #ddd;
}
 #full-stars-example-two .rating-group:hover .rating__label .rating__icon--star {
	 color: orange;
}
 #full-stars-example-two .rating__input:hover ~ .rating__label .rating__icon--star {
	 color: #ddd;
}
    </style>

<body>

<?php 
if(empty($maintenanceData)) {
?>

<section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0" style="width: 50rem;">
                        <div class="card-header text-center">
                           Thank you !
                        </div>
                        <div class="card-body text-center thankyou_card" style = "font-weight: bold;">
                        You have already provided the feedback!
                        </div>
                        <div class="card-footer text-center">
                        Please enjoy your stay!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php 
} else {
$guestFname = $maintenanceData['FirstName'];

$current_date = date("Y-m-d H:i:s");
if($maintenanceData['FeedBackRequestRead'] == NULL){
$db->query(' UPDATE MaintenanceTicket SET FeedBackRequestRead = ? WHERE TicketNum = ?', $current_date, $ticketNumber);
}
$db->query(' UPDATE EmailQueue SET NotificationRead = ? WHERE TicketNum = ? AND noFlagEmails=? AND Type=?', $current_date, $ticketNumber,"1", "RequestFeedBackEmail");



?>
<section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0" >
                        <div class="card-header text-center">
                        <strong>Please tell us how we did.</strong>

                        </div>

                        <div class="card-body  thankyou_card">
                           <p> Hey <?php echo $guestFname; ?> ! </p>
                            <p>My family and I take great pride in responding to maintenance 
                                issues and our Guests’ needs.
                            </p>

                            <p>Your feedback is extremely valuable in helping us to ensure 
                                that create an outstanding we experience. </p>

                            <p>Additionally. we partially base our team’s compensation on 
                                our Guests’ feedback.
                            </p>
                           
                            <p>I would appreciate it personally
                                if you could take a brief 
                                moment to let us know how we 
                                did in resolving the issue below </p>

                            <p> Thank you! And please enjoy your stay!</p>


                            <div class="row mt-4 mb-4">
                                    <div class="brand col-md-4">
                                        <a href="/"><img src="img/Profile_pic.png" alt="We Care" width="150px"></a>
                                    </div>
                                    <div class="brand col-md-4">
                                        <a href="/"><img src="img/todd_sign.png" alt="We Care" width="150px"></a>
                                    </div>
                            </div>

                            <p><span style = "font-weight: bold;">Property : </span><?= $maintenanceData['Property']; ?> <br>
                            <span style = "font-weight: bold;">Issue Date/Time :</span> <?php echo date("m-d-Y", strtotime($maintenanceData['TicketDate']) )." ".date("h:i A", strtotime($maintenanceData['TicketTime']) ); ?> <br>
                            <span style = "font-weight: bold;">General Issue :</span> <?= $maintenanceData['Issue']; ?><br> 
                            <?php if($teamMemberName) {?>
                            <span style = "font-weight: bold;">Team Member : </span> <?= $teamMemberName; ?><br>
                                <?php } ?>
                            <?php if($maintenanceData['ClosedDate'] != "" || $maintenanceData['ClosedDate'] != NULL) { ?> 
                            <span style = "font-weight: bold;">Date/time resolved : </span> <?php echo date("m-d-Y", strtotime($maintenanceData['ClosedDate']) )." ".date("h:i A", strtotime($maintenanceData['ClosedTime']) ); ?> </p>

                           <?php } ?>

                           <p> Your overall satisfaction with how we handled this issue:</p>
                            <form method="POST" action ="thankyouMsg.php">
                            <div class="">
                                <div id="full-stars-example-two">
                                    <div class="rating-group">
                                        <input disabled checked class="rating__input rating__input--none" name="rating3" id="rating3-none" value="0" type="radio">
                                        <label aria-label="1 star" class="rating__label" for="rating3-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating3-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
                                        <label aria-label="3 stars" class="rating__label" for="rating3-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
                                        <label aria-label="4 stars" class="rating__label" for="rating3-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating3-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
                                    </div>
                                </div>
                            </div>
                                <label style = "font-weight: bold;" for="">Additional feedback (optional)</label>
                                <textarea class="form-control" name="feedback" rows="5" id="feedback"></textarea>
                                    <div class="text-right"><span class="feedbackLength" id="feedback_length">250</span>
                                    characters
                                    remaining </div> 

                                    <input type="hidden" name="TicketNum" value="<?= $maintenanceData['TicketNum']; ?>" id="TicketNum" ></input>
                                
                                </div>
                                <div class="card-footer text-center">
                                <button type="submit" name="feedbacksubmit" value ="feedbacksubmit" id="feedbacksubmit"
                                    class="btn btn-primary">Submit</button>
                                </div>
                                <input type="hidden" name="starvalue" value="0" id="answer" class="starvalue"></input>
                            </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var maxchars = 250;
        $('#feedback').keyup(function() {
            var tlength = $(this).val().length;
            $(this).val($(this).val().substring(0, maxchars));
            var tlength = $(this).val().length;
            remain = maxchars - parseInt(tlength);
            $('#feedback_length').text(remain);
        });

        $('.rating__input[type=radio]').click(function() { 
           
            var starvalue = $(this).val();
            $(".starvalue").val(starvalue);
            // alert(starvalue);
        });

    });
    </script>
<?php } ?>
</body>
</html>