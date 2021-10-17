<?php
include 'public/public.php';
include 'public/conn_php.php';
$tim_sql = !empty($_POST['select']) ? "where {$_POST['select']}" : "";
if ($tim_sql != "") {
    $tim_sql = preg_replace("/(:'.*?[\w\s])(')([\w].*?'(?:\||))/i", '${1}\'\'$3', $tim_sql);
    $tim_sql = str_replace(":", "=", $tim_sql);
    $tim_sql = str_replace("|", " and ", $tim_sql);
}
$draw = $_POST['draw']; //第几次请求
//排序
$order_column = $_POST['order']['0']['column']; // 哪一列排序
$order_dir= $_POST['order']['0']['dir']; // ase desc 升序或者降序
//拼接排序sql
$orderSql = "";
//排序
$t_marge = array(
    "0" => "GeneName",
    "1" => "GeneType",
    "2" => "Interacting_Gene_Symbol",
    "3" => "CellName",
    "4" => "CellType",
    "5" => "TissueType",
    "6" => "id"
);
for ($i = 0;$i< count($_POST['order']);$i++){
    $order[] = "{$t_marge[$_POST['order'][strval($i)]['column']]} {$_POST['order'][strval($i)]['dir']}";
}
$orderSql = " order by ".join(',',$order);

//定义查询数据总记录数sql
$sumSql = "SELECT count(1) as sum FROM $main $tim_sql";
//条件过滤后记录数 必要
$recordsFiltered = 0;
//表的总记录数 必要
$recordsTotal = 0;
$recordsTotalResult = mysqli_query($conn,$sumSql);
while($row=mysqli_fetch_assoc($recordsTotalResult)){
	$recordsTotal = $row['sum'];
}
//分页
$start = $_POST['start'];//从多少开始
$length = $_POST['length'];//数据长度
$join = '';
$limitSql = '';
$limitFlag = isset($_POST['start']) && $length != -1 ;
if ($limitFlag ) {
    $joinSql = " JOIN (select id from $main $tim_sql ";
    $limitSql = " limit ".intval($start).", ".intval($length).") b ON TEM.id = b.id ";
}
//搜索//定义过滤条件查询过滤后的记录数sql
$search = $_POST['search']['value'];//获取前台传过来的过滤条件
$zSearchSql = "";
$totalResultSql = "SELECT * FROM $main AS TEM ";
$sql='';
if(!empty($_POST["search"]["value"])){
    $search = $_POST["search"]["value"];
    if(empty($tim_sql)) $sel = " where ";
    else $sel = " and ";
    $zSearchSql = " $sel (
                    GeneName LIKE '%$search%'
                    OR GeneType LIKE '%$search%'
                    OR Interacting_Gene_Symbol LIKE '%$search%'
                    OR CellName LIKE '%$search%'
                    OR CellType LIKE '%$search%'
                    OR TissueType LIKE '%$search%'
                    ) ";
    $recordsFilteredResult = mysqli_query($conn,"SELECT count(1) as sum FROM $main $tim_sql $zSearchSql");
    while ($row = mysqli_fetch_assoc($recordsFilteredResult)) {
        $recordsFiltered =  $row['sum'];
    }
}
else{
    $recordsFiltered = $recordsTotal;
}
$sql=$totalResultSql.$joinSql.$zSearchSql.$orderSql.$limitSql;
$infos = array();
$dataResult = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($dataResult)) {
    array_push($infos,$row);
}
// return data
echo json_encode(array(
	"draw" => $draw,
	"recordsTotal" =>$recordsTotal,  // necessary
	"recordsFiltered" =>$recordsFiltered, // necessary
	"data" =>$infos, // necessary
    "sql" => $sql,
	),JSON_UNESCAPED_UNICODE);
//print_r ($infos);
?>