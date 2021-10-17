<?php
$tis_type = $_POST['tis_type'];
$cel_type = $_POST['cel_type'];
$cel_name = $_POST['cel_name'];
$Gen_type = $_POST['Gen_type'];
$input_sel = $_GET['input_sel'];
if(!empty($tis_type))
    $selects[] = "TissueType='$tis_type'";
if(!empty($cel_type))
    $selects[] = "CellType='$cel_type'";
if(!empty($cel_name))
    $selects[] = "CellName='$cel_name'";
if(!empty($Gen_type))
    $selects[] = "GeneType='$Gen_type'";
if(count($selects)>0)
    $select = " where ".join(" and ",$selects);
else
    $select = "";
include '../public/conn_php.php';
$search="SELECT distinct $input_sel
                from $main
                 $select
                 order by $input_sel";
$search_result=mysqli_query($conn,$search);
while($row = mysqli_fetch_assoc($search_result)){
    $data[] = array(
        "label" => $row[$input_sel]
    );
}
//echo $search;
echo json_encode($data);
?>