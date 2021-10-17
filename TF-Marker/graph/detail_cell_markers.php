<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
include(__DIR__ . "/../public/conn_php.php");
$CellName = urldecode($_POST['CellName']);
$select =  urldecode($_POST['select']);
if(!empty($CellName)) {
    if (!empty($select)) {
        $select .= " and CellName='$CellName'";
    } else
        $select .= " where CellName='$CellName'";
}

$sample_table_sql = "SELECT CellName,GeneName from $main $select";
$sample_table_result = mysqli_query($conn, $sample_table_sql);
$data = [];
while ($rows = mysqli_fetch_assoc($sample_table_result)) {
    $GeneName = $rows["GeneName"];
    if (empty(trim($rows["CellName"])) || empty(trim($GeneName))) continue;
    if(empty($data[$GeneName]))
        $data[$GeneName] = 1;
    else
        $data[$GeneName] = $data[$GeneName] + 1;
}
$rs = [];
foreach ($data as $name => $weight){
    array_push($rs,array(
        "name" => $name,
        "weight" => $weight
    ));
}
echo json_encode($rs);
?>
