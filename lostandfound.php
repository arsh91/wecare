<?php
	include 'db_connection.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>We Care || Lost found Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="divLoading">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-center">
                    <div class="loading">
                        <p>Your pictures will now be uploaded.</p>
                        <p>It may appear that your screen is “locked up” or “frozen” for up to 60 seconds.</p>
                        <p>This is normal. Please allow at least 60 seconds for the upload to complete.</p>
                        <p>Please do not click the back button or refresh until the upload completes.</p>
                        <img src="img/loader.gif">
                    </div>
                </div>
            </div>
        </div>
    </div>

<section class="driver_form m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <form method="POST" name="lost_found_details" action="lost_success_page.php" class="needs-validation golfcartForm"
                        id="lost_found_details" enctype="multipart/form-data" novalidate>
                        <div class="card col-md-8 m-auto p-0">
                            <div class="card-header">
                                <div class="inner_card_header text-center  m-auto">
                                    <h4>Lost and Found</h4>
                                   
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="drivers_det">
                                    <div class="form-group">
                                        <div class="form-group mb-3">
                                        <p>We’re sorry to hear that you 
                                           may have left items behind.
                                        </p>

                                        <p>Although we are unable to 
                                            search the property while it is 
                                            occupied by other Guests, we 
                                            will do our best to locate your 
                                            item during our next vacancy.
                                        </p>
                                        <p>All lost items are packaged by 
                                            the UPS Store and shipped via 
                                            UPS. Guests are charged the 
                                            exact cost that the UPS Store 
                                            charges for packaging and 
                                            shipping. 
                                        </p>
                                        <p>Please provide the information 
                                            below so that, if we are able to 
                                            locate your lost we are item, we        will be  
                                            able to return it to you as soon 
                                            as possible.
                                        </p>
                                        <p>We will keep you updated by 
                                            text and email as information 
                                            becomes available:
                                        </p><br>
                                            <label for="property_name">Property Name</label>
                                            <?php $properties = $db->query('SELECT * FROM Properties ORDER BY PropertyName ASC')->fetchAll(); ?>
                                            <select class="form-control form-select propertyselect" id="property"
                                                name="property" aria-label="Default select example" required>
                                                <option value="">Select Property Name</option>
                                                <?php foreach ($properties as $row) {?>
                                                <option class="property" propertyId=<?php echo $row['PropertyID']; ?>
                                                    value="<?php echo $row['PropertyName']; ?>">
                                                    <?php echo $row['PropertyName']; ?>
                                                </option>
                                                <?php }   ?>
                                            </select>
                                        </div>
                                        

                                        <div class="form-group mb-3">
                                            <label for="firstname">Your First Name</label>
                                            <input type="text" name="firstname" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="lastname">Your Last Name</label>
                                            <input type="text" name="lastname" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="checkoutdate">Check Out Date</label>
                                            <input type="date" id="checkoutdate" name="checkoutdate" class="form-control" required>
                                        </div>
                                       
                                        <div class="form-group mb-3">
                                            <label for="phone">Cell phone (able to receive texts)</label>
                                            <input type="tel" name="phone" id="phone" class="form-control"
                                                maxlength="20" required>
                                            <span id="errphonemsg"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" required>
                                        </div>
                                       
                                        <div class="form-group mb-3">
                                            <label for=""><strong>Select Shipping Method:</strong></label>
                                            <div class="form-check">
                                                <input
                                                        class="form-check-input" type="radio" value="least_expensive"
                                                        name="shipping_method_radio" id="least_slower" required>
                                                        <label class="form-check-label shipping_method_radio" for="least_slower"> Least expensive/slower method
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                        class="form-check-input" type="radio" value="most_expensive"
                                                        name="shipping_method_radio" id="most_fastest" required>
                                                        <label class="form-check-label shipping_method_radio" for="most_fastest">Most expensive/fastest method 

                                                </label>
                                            </div> 
                                        </div>
                                      
                                        <div class="form-group input-group mb-3">
                                            <label class="file_fields" for="file1"><strong>Provide Payment Method:</strong></label>
                                            <label class="file_fields" for="file1">Select Front of Credit Card:</label>
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file1" id="file1"
                                                    class="custom-file-input " required>
                                                <label class="custom-file-label show_desktop_view" for="file1">Choose
                                                    file</label>
                                                <label class="custom-file-label show_mobile_view" for="file1">Take
                                                    Pic</label>
                                            </div>
                                            <div class="img_preview file1_preview">
                                                <img id="file1_img" src="" class="preview_file1" />
                                                <input type="button" id="remove_btn1" value="x" class="btn-rmv1" />
                                            </div>
                                        </div>
                                        <div class="form-group input-group mb-3">
                                            <label class="file_fields" for="file1">Select Back of Credit Card:</label>
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file2" id="file2"
                                                    class="custom-file-input " required>
                                                <label class="custom-file-label show_desktop_view" for="file2">Choose
                                                    file</label>
                                                <label class="custom-file-label show_mobile_view" for="file2">Take
                                                    Pic</label>
                                            </div>
                                            <div class="img_preview file2_preview">
                                                <img id="file2_img" src="" class="preview_file2" />
                                                <input type="button" id="remove_btn2" value="x" class="btn-rmv2" />
                                            </div>
                                        </div>
                                    
                                         <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="authorize"
                                                required>
                                            <label class="form-check-label" for="authorize">
                                            I authorize my credit card to be charged for shipping and packaging charges incurred for the return of my above-described item.</label><br><br>

                                            <div class="invalid-feedback lostErrorMsg">
                                            Please correct the errors above that are highlighted in red
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="form-group">
                                    <button type="submit" name="submit"
                                        class="btn btn-primary submitBTN">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/custom.js"></script>

</html>
