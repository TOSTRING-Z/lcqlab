<?php
$gene_name = $_POST['gene_name'];
$gene_type = $_POST['gene_type'];
$input_sel = $_GET['input_sel'];
if(!empty($gene_name))
    $selects[] = "GeneName='$gene_name'";
if(!empty($gene_type))
    $selects[] = "GeneType='$gene_type'";
if(count($selects)>0)
    $select = " where ".join(" and ",$selects);
else
    $select = "";
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include '../public/conn_php.php';
$search="SELECT distinct $main.$input_sel
FROM $main
$select";
$search_result=mysqli_query($conn,$search);
while($row = mysqli_fetch_assoc($search_result)){
    $data[] = array(
        "label" => $row[$input_sel]
    );
}
//echo $search;
echo json_encode($data);
?>