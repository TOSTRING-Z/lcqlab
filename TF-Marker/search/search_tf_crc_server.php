<?php
$tf_name = $_POST['tf_name'];
$tis_type = $_POST['tis_type_two'];
$cel_type = $_POST['cel_type_two'];
$input_sel = $_GET['input_sel'];
if(!empty($tf_name))
    $selects[] = "GeneName='$tf_name'";
if(!empty($tis_type))
    $selects[] = "TissueType='$tis_type'";
if(!empty($cel_type))
    $selects[] = "CellType='$cel_type'";
if(count($selects)>0)
    $select = " on ".join(" and ",$selects)." and ";
else
    $select = " on ";
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include '../public/conn_php.php';
$search="SELECT distinct $main.$input_sel
FROM $main
join $tf_information
$select
GeneName = tf_information_gene_symbol";
$search_result=mysqli_query($conn,$search);
while($row = mysqli_fetch_assoc($search_result)){
    $data[] = array(
        "label" => $row[$input_sel]
    );
}
//echo $search;
echo json_encode($data);
?>