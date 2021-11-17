<?php
	include 'db_connection.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>We Care || link Generate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    
    <section class="driver_form m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper " style="width:50%;">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <!-- <form method="POST"  action="" id="linkGenerate" enctype="multipart/form-data" > -->
                        <div class="card col-md-12 m-auto p-0">
                            <div class="card-header">
                                <div class="inner_card_header m-auto">
                                    <h4 class="text-center m-0">Generate your link here</h4>
                                   
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="drivers_det">
                                    <div class="form-group">
                                        <div class="form-group mb-3">
                                            <label for="property_name">Property Name</label>
                                            <?php $properties = $db->query('SELECT * FROM Properties ORDER BY PropertyName ASC')->fetchAll(); ?>
                                            <select class="form-control form-select propertyselect" id="property"
                                                name="property" aria-label="Default select example">
                                                <option value="">Select Property Name</option>
                                                <?php foreach ($properties as $row) {?>
                                                <option class="property" propertyId=<?php echo $row['PropertyID']; ?>
                                                    value="<?php echo $row['PropertyName']; ?>">
                                                    <?php echo $row['PropertyName']; ?>
                                                </option>
                                                <?php }   ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="propertyid">

                                        <div class="form-group mb-3">
                                            <label for="firstname">Your First Name</label>
                                            <input type="text" id="firstname" name="firstname" class="form-control" >
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone">Your Phone Number</label>
                                            <input type="tel" name="phone" id="Phone" class="form-control"
                                                maxlength="20" >
                                           
                                        </div>
                                        <div class="linkGenerateMsg"></div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="form-group">
                                    <button type="submit" name="submit"
                                        class="btn btn-primary linkSubmit m-0">Generate Link</button>
                                </div>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </section>
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/custom.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.linkSubmit').click(function() {

            var propertyName = $.trim($('#property').find(":selected").text());
            var Fname =$("#firstname").val();
            var Phone =$("#Phone").val();
            
            $('.linkGenerateMsg').html(
                        '<div class="alert alert-success mt-3 link_generate_msg" role="alert"><p>Please copy the Link:</p><p>http://wecare.equisourceholdings.com/?property='+propertyName+'&fname='+Fname+'&phone='+Phone+'</p></div>'
                    );
        });
        
        // function phoneMask() { 
        //     var num = $(this).val().replace(/\D/g,''); 
        //     $(this).val('+1 ' + num.substring(1,4) + '-' + num.substring(4,7) + '-' + num.substring(7,11)); 
        // }
        // $('[type="tel"]').keyup(phoneMask);

    });
    </script>
</body>

</html>