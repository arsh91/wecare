<?php
include 'db_connection.php';


if(isset($_POST['PropertyID'])){

    $PropertyId= $_POST['PropertyID'];
    $categorybox= $db->query('SELECT Category, PropertyID, CategoryID FROM MaintenanceAssignements WHERE PropertyID=?',  $PropertyId)->fetchAll();

    $value="";
    foreach($categorybox as $val){
        $value=$value."<option categoryId='".$val['CategoryID']."' value='".$val['Category']."'>".$val['Category']."</option>";
    }

    echo json_encode($value);
    exit;
}
?>