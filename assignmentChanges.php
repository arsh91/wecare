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
    <style>
th:first-child, td:first-child
{
  position:sticky;
  left:0px;
  background-color:#dee2e6;
}
        </style>
</head>
<?php
 
$assignmentsData = $db->query('SELECT DISTINCT PropertyID, PropertyName FROM MaintenanceAssignements')->fetchAll(); 
// echo "<pre>"; print_r($PropertyName); echo "</pre>"; 
$categoriesData = $db->query('SELECT DISTINCT CategoryID, Category FROM MaintenanceAssignements')->fetchAll(); 

?>
<body>
   
    <section class="driver_form m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <form method="POST" name="assigmnetsChanges" action="" class="needs-validation"
                        id="assigmnetsChanges" enctype="multipart/form-data" novalidate>
                        <div class="card col-md-12 m-auto p-0" style="width: 1350px;">
                            <div class="card-header">
                                
                            </div>
                            <div class="card-body">
                                <div class="drivers_det">
                                    <div class="form-group">
                                                
                                       <h5>Change Maintenance Assignments 
                                        for these properties:
                                        </h5> 
                                        <div class="row mt-3 mb-3">
                                                <?php foreach($assignmentsData as $key=> $assignmentData){ ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="<?php
                                                    echo $assignmentData['PropertyID'];?>" id="authorize"
                                                        required>
                                                    <label class="form-check-label me-2" for="authorize"><?php
                                                    echo $assignmentData['PropertyName']; ?><label><br>                                        
                                                </div>
                                                <?php } ?>                                           
                                        </div>
                                    <div class="assignment_table_div">
                                        <table class="table mt-4 mb-3 assignment_table">
                                            <tr>
                                                <th>For these assignments:</th>
                                                <th>Contact1</th>
                                                <th>Wait1</th>
                                                <th>Contact2</th>
                                                <th>Wait2</th>
                                                <th>Contact3</th>
                                                <th>Wait3</th>
                                                <th>Contact4</th>
                                                <th>Wait4</th>
                                                <th>Contact5</th>
                                                <th>Wait5</th>
                                                <th>Contact6</th>
                                                <th>Wait6</th>
                                                <th>Contact7</th>
                                                <th>Wait7</th>
                                                <th>Contact8</th>
                                                <th>Wait8</th>
                                                <th>Contact9</th>
                                                <th>Wait9</th>
                                                <th>Contact10</th>
                                                <th>Wait10</th>                                               
                                            </tr>
                                            <tbody>
                                          
                                            <?php foreach($categoriesData as  $categoryData) { ?>
                                                <tr>
                                                  
                                                    <td><?php  echo $categoryData['Category']; ?>  </td>
                                               
                                                    <td> <input class="inputBox" type="number" id="contact1" name="contact1"></td>
                                                    <td><input class="inputBox" type="number" id="wait1" name="wait1"></td> 
                                                    <td><input class="inputBox" type="number" id="contact2" name="contact2"></td>
                                                    <td><input class="inputBox" type="number" id="wait2" name="wait2"></td>
                                                    <td><input class="inputBox" type="number" id="contact3" name="contact3"></td>
                                                    <td><input class="inputBox" type="number" id="wait3" name="wait3"></td>
                                                    <td><input class="inputBox" type="number" id="contact4" name="contact4"></td>
                                                    <td><input class="inputBox" type="number" id="wait4" name="wait4"></td>
                                                    <td><input class="inputBox" type="number" id="contact5" name="contact5"></td>
                                                    <td><input class="inputBox" type="number" id="wait5" name="wait5"></td>
                                                    <td><input class="inputBox" type="number" id="contact6" name="contact6"></td>
                                                    <td><input class="inputBox" type="number" id="wait6" name="wait6"></td>
                                                    <td><input class="inputBox" type="number" id="contact7" name="contact7"></td>
                                                    <td><input class="inputBox" type="number" id="wait7" name="wait7"></td>
                                                    <td><input class="inputBox" type="number" id="contact8" name="contact8"></td>
                                                    <td><input class="inputBox" type="number" id="wait8" name="wait8"></td>
                                                    <td><input class="inputBox" type="number" id="contact9" name="contact9"></td>
                                                    <td><input class="inputBox" type="number" id="wait9" name="wait9"></td>
                                                    <td><input class="inputBox" type="number" id="contact10" name="contact10"></td>
                                                    <td><input class="inputBox" type="number" id="wait10" name="wait10"></td>



                                                </tr>
                                            <?php } ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center">
                               
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