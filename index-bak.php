<?php
	include 'db_connection.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>We Care</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div id="divLoading">
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
                    <form method="POST" name="driver_details" action="success.php" class="needs-validation golfcartForm"
                        id="driver_details" enctype="multipart/form-data" novalidate>
                        <div class="card col-md-12 m-auto p-0">
                            <div class="card-header">
                                <div class="inner_card_header m-auto">
                                    <h4>The fastest way to:</h4>
                                    <ul class="p-0">
                                        <li>Request Maintenance</li>
                                        <li>Report An Issue</li>
                                        <li>Request Missing Items</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="drivers_det">
                                    <div class="form-group">
                                        <div class="form-group mb-3">
                                            <label for="property_name">Property Name</label>
                                            <?php $properties = $db->query('SELECT * FROM Properties ORDER BY PropertyName ASC')->fetchAll(); ?>
                                            <select class="form-control form-select" name="property"
                                                aria-label="Default select example" required>
                                                <option value="">Select Property Name</option>
                                                <?php foreach ($properties as $row) {?>
                                                <option value="<?php echo $row['PropertyName']; ?>">
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
                                            <label for="phone">Your Phone Number</label>
                                            <input type="tel" name="phone" id="phone" class="form-control"
                                                maxlength="20" required>
                                            <span id="errphonemsg"></span>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="">I will be 100% satisfied if
                                                this issue is resolved:</label>
                                            <div class="form-check">
                                                <label class="form-check-label" for="Immediate"><input
                                                        class="form-check-input" type="radio" value="Immediate"
                                                        name="satisfied_radio" id="Immediate" checked required>
                                                    Immediately (Emergency
                                                    or substantially impacting
                                                    your stay)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="4-Hours"><input
                                                        class="form-check-input" type="radio" value="4 Hours"
                                                        name="satisfied_radio" id="4-Hours">
                                                    Within the next 4 hours
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="Tomorrow"><input
                                                        class="form-check-input" type="radio" value="Tomorrow"
                                                        name="satisfied_radio" id="Tomorrow">
                                                    Between 9A-5P tomorrow
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="Turn"><input
                                                        class="form-check-input" type="radio" value="Turn"
                                                        name="satisfied_radio" id="Turn">
                                                    After my checkout. I’m just reporting the issue
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="basic_issue">The basic issue is:</label>
                                            <?php $Category = $db->query('SELECT * FROM MaintenanceCategories')->fetchAll(); ?>
                                            <select class="form-control form-select custom_val" name="issue"
                                                aria-label="Default select example" required id="basic_issue">
                                                <option value="">Select Issue</option>
                                                <?php foreach ($Category as $row) { 
                                                    if($row['Category'] != '') {
                                                    ?>
                                                <option value="<?php echo $row['Category'];  ?>">
                                                    <?php echo $row['Category']; ?>
                                                </option>
                                                <?php } }  ?>
                                            </select>
                                        </div>
                                        <div class="error-message"
                                            style="font-size:14px; display:none; margin-top:-15px; margin-bottom:15px; color:red;">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="">A more detailed explanation is:</label>
                                            <textarea class="form-control rounded-0" id="IssueDescription"
                                                name="IssueDescription" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group input-group mb-3">
                                            <label class="file_fields" for="file1">Picture(s) of this issue
                                                (optional):</label>
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file1" id="file1"
                                                    class="custom-file-input ">
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
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file2" id="file2"
                                                    class="custom-file-input ">
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
                                        <div class="form-group input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file3" id="file3"
                                                    class="custom-file-input ">
                                                <label class="custom-file-label show_desktop_view" for="file3">Choose
                                                    file</label>
                                                <label class="custom-file-label show_mobile_view" for="file3">Take
                                                    Pic</label>
                                            </div>
                                            <div class="img_preview file3_preview">
                                                <img id="file3_img" src="" class="preview_file3" />
                                                <input type="button" id="remove_btn3" value="x" class="btn-rmv3" />
                                            </div>
                                        </div>
                                        <div class="form-group input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" accept="image/*" name="file4" id="file4"
                                                    class="custom-file-input ">
                                                <label class="custom-file-label show_desktop_view" for="file4">Choose
                                                    file</label>
                                                <label class="custom-file-label show_mobile_view" for="file4">Take
                                                    Pic</label>
                                            </div>
                                            <div class="img_preview file4_preview">
                                                <img id="file4_img" src="" class="preview_file4" />
                                                <input type="button" id="remove_btn4" value="x" class="btn-rmv4" />
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
    <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/custom.js"></script>
</body>

</html>