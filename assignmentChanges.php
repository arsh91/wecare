<?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
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
 //FECTH DISTINCT PROPERTY DATA FROM MAINTENANCE ASSIGNMENTS
$assignmentsData = $db->query('SELECT DISTINCT  PropertyID, PropertyName FROM MaintenanceAssignements')->fetchAll(); 
// echo "<pre>"; print_r($assignmentsData); echo "</pre>"; die;

//FECTH DISTINCT CATEGORY DATA FROM MAINTENANCE ASSIGNMENTS
$categoriesData = $db->query('SELECT DISTINCT CategoryID, Category FROM MaintenanceAssignements')->fetchAll(); 
// echo "<pre>"; print_r($categoriesData); echo "</pre>"; 

//FECTH CONTACT,WAIT,TECNOTES DATA FROM MAINTENANCE ASSIGNMENTS
$MaintenanceAssignements = $db->query('SELECT * FROM MaintenanceAssignements WHERE PropertyName ="Aqua"')->fetchAll();
// echo "<pre>"; print_r($MaintenanceAssignements); echo "</pre>"; 

$success = 0;

if(isset($_POST['submit'])){
   print_r('test');
echo "<pre>"; print_r($_POST); echo "</pre>";
    foreach($_POST['property'] as $prop){
        // echo "<pre>"; print_r($prop); echo "</pre>";

        foreach($_POST['category'] as $cat){
            $cat = (!empty($cat)) ? $cat : "NULL"; 
        // echo "<pre>"; print_r($cat); echo "</pre>";
        $updateMaintenanceAssignements = $db->query('UPDATE MaintenanceAssignements SET Contact1 =?, Wait1 =?, Contact2 =?, Wait2 =?, Contact3 =?, Wait3 =?, Contact4 =?, Wait4 =?, Contact5 =?, Wait5 =?, Contact6 =?, Wait6 =?, Contact7 =?, Wait7 =?, Contact8 =?, Wait8 =?, Contact9 =?, Wait9 =?, Contact10 =?, Wait10 =?, technotes =? WHERE PropertyID=? AND CategoryID=?', $cat['contact1'], $cat['wait1'], $cat['contact2'], $cat['wait2'], $cat['contact3'], $cat['wait3'], $cat['contact4'], $cat['wait4'], $cat['contact5'], $cat['wait5'], $cat['contact6'], $cat['wait6'], $cat['contact7'], $cat['wait7'], $cat['contact8'], $cat['wait8'], $cat['contact9'], $cat['wait9'], $cat['contact10'], $cat['wait10'], $cat['technotes'], $prop, $cat['id']);
            
        $success = 1;
        } 

    }
    
}


function getRealPOST() {
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}
?>
<body>
   
    <section class="driver_form m-4">
        <div class="container">
            <div class="row justify-content-md-center align-items-center h-100">
                <div class="card_wrapper ">
                    <div class="brand text-center mb-4">
                        <a href="/"><img src="img/wecarelogo.png" alt="We Care" width="150px"></a>
                    </div>
                    <form method="POST" name="assigmnetsChanges" action="" 
                        id="assigmnetsChanges" enctype="multipart/form-data">
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
                                                    <input class="form-check-input" name="property[<?php
                                                    echo $assignmentData['PropertyID']; ?>]" type="checkbox" value="<?php
                                                    echo $assignmentData['PropertyID'];?>" id="authorize"
                                                        >
                                                    <label class="form-check-label me-2" for="authorize"><?php
                                                    echo $assignmentData['PropertyName']; ?><?php
                                                    echo $assignmentData['PropertyID'];?><label><br>                                        
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
                                                <th>TechaNotes</th>                                               

                                            </tr>
                                            <tbody>
                                          
                                            <?php foreach($categoriesData as  $key =>$categoryData) {
                                              
                                                    if (strpos($categoryData['Category'], "------")  === false) {
                                                       
                                            ?>
                                                <tr>
                                                  
                                                    <td><?php  echo $categoryData['Category']; ?> <?php  echo $categoryData['CategoryID'];
                                                    ?> 
                                                    <input type="hidden" name="category[<?php echo $categoryData['CategoryID']; ?>][id]" value="<?php echo $categoryData['CategoryID']; ?>">
                                                </td>
                                             
                                                    <td> <input class="inputBox"  type="number" id="contact1" name="category[<?php echo $categoryData['CategoryID']; ?>][contact1]"></td>
                                                    <td><input class="inputBox" type="number" id="wait1" name="category[<?php echo $categoryData['CategoryID']; ?>][wait1]"></td> 
                                                    <td><input class="inputBox" type="number" id="contact2" name="category[<?php echo $categoryData['CategoryID']; ?>][contact2]"></td>
                                                    <td><input class="inputBox" type="number" id="wait2" name="category[<?php echo $categoryData['CategoryID']; ?>][wait2]"></td>
                                                    <td><input class="inputBox" type="number" id="contact3" name="category[<?php echo $categoryData['CategoryID']; ?>][contact3]"></td>
                                                    <td><input class="inputBox" type="number" id="wait3" name="category[<?php echo $categoryData['CategoryID']; ?>][wait3]"></td>
                                                    <td><input class="inputBox" type="number" id="contact4" name="category[<?php echo $categoryData['CategoryID']; ?>][contact4]"></td>
                                                    <td><input class="inputBox" type="number" id="wait4" name="category[<?php echo $categoryData['CategoryID']; ?>][wait4]"></td>
                                                    <td><input class="inputBox" type="number" id="contact5" name="category[<?php echo $categoryData['CategoryID']; ?>][contact5]"></td>
                                                    <td><input class="inputBox" type="number" id="wait5" name="category[<?php echo $categoryData['CategoryID']; ?>][wait5]"></td>
                                                    <td><input class="inputBox" type="number" id="contact6" name="category[<?php echo $categoryData['CategoryID']; ?>][contact6]"></td>
                                                    <td><input class="inputBox" type="number" id="wait6" name="category[<?php echo $categoryData['CategoryID']; ?>][wait6]"></td>
                                                    <td><input class="inputBox" type="number" id="contact7" name="category[<?php echo $categoryData['CategoryID']; ?>][contact7]"></td>
                                                    <td><input class="inputBox" type="number" id="wait7" name="category[<?php echo $categoryData['CategoryID']; ?>][wait7]"></td>
                                                    <td><input class="inputBox" type="number" id="contact8" name="category[<?php echo $categoryData['CategoryID']; ?>][contact8]"></td>
                                                    <td><input class="inputBox" type="number" id="wait8" name="category[<?php echo $categoryData['CategoryID']; ?>][wait8]"></td>
                                                    <td><input class="inputBox" type="number" id="contact9" name="category[<?php echo $categoryData['CategoryID']; ?>][contact9]"></td>
                                                    <td><input class="inputBox" type="number" id="wait9" name="category[<?php echo $categoryData['CategoryID']; ?>][wait9]"></td>
                                                    <td><input class="inputBox" type="number" id="contact10" name="category[<?php echo $categoryData['CategoryID']; ?>][contact10]"></td>
                                                    <td><input class="inputBox" type="number" id="wait10" name="category[<?php echo $categoryData['CategoryID']; ?>][wait10]"></td>
                                                    <td><input class="inputtechNotes" type="text" id="techNotes" name="category[<?php echo $categoryData['CategoryID']; ?>][technotes]"></td>

                                                        
                                                </tr>
                                                <?php
                                               
                                                    }
                                                     
                                                 }
                                                
                                                 ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center">
                                <div class="form-group">
                                    <?php if($success){
                                    ?>
                                    <div class="success_box">
                                        <div class="alert alert-success mt-3 eta_success_msg" role="alert">MaintenanceAssignements updated successfully!</div>
                                    </div>
                                    <?php } ?>
                                    <button type="submit" name="submit"
                                        class="btn btn-primary">Submit</button>
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
    <!-- <script src="js/custom.js"></script> -->
</body>
<script type="text/javascript">
$(document).ready(function() {
    
// Append the Alert Box 
setTimeout(() => {
        $('.success_box').fadeOut('slow');
    }, 2000);
});
</script>
</html>