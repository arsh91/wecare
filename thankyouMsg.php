<?php
include 'db_connection.php';
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>   
 <?php
    if(isset($_POST['feedbacksubmit'])){
        
        $ticketNumber = $_POST['TicketNum'];
        $guestRating= $_POST['starvalue'];
        $guestFeedback = $_POST['feedback'];
        
        $db->query('UPDATE MaintenanceTicket SET  GuestRating = ? , GuestFeedback=?  WHERE TicketNum=?',$guestRating, $guestFeedback, $ticketNumber);
        ?>
<body>
        <section class="thank_you m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <div class="card col-md-8 m-auto p-0">
                        <div class="card-header text-center">
                           Thank you !
                        </div>
                        <div class="card-body text-center thankyou_card" style = "font-weight: bold;">
                        Thank you for providing feedback!
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
    }
    ?>
    </body>
</html>