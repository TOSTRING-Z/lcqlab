<?php include "../public/public.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
    <style>
        .info-more {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            max-width: 100px;
            position: relative;
            padding-right: 31px;
        }
        .info-more > i {
            position: absolute;
            right: 2px;
            top: -4px;
            font-size: 22px;
        }
    </style>
</head>
<body>
<?php
$tis_type = $_GET['tis_type'];
$cel_type = $_GET['cel_type'];
$cel_name = $_GET['cel_name'];
function rm_null($item){
    return (!empty($item));
}
$select_type = array_filter([
    empty($tis_type)?null:"TissueType='$tis_type'",
    empty($cel_type)?null:"CellType='$cel_type'",
    empty($cel_name)?null:"CellName='$cel_name'"
],"rm_null");
if (count($select_type)>0)
    $select_type = "where ".join(" and ",$select_type);
else
    $select_type = "";
?>
<div class="container-fluid" id="body">

    <div class="row">
        <div class="col-lg-12">
            <h4>Currently,
                the tissue type selected by the user is <b><font color="red"><?php echo empty($tis_type)?"all":$tis_type?></font></b>,
                the cell type is <b><font color="red"><?php echo empty($cel_type)?"all":$cel_type ?></font></b>,
                the cell name is <b><font color="red"><?php echo empty($cel_name)?"all":$cel_name ?></font></b>.
            </h4>
        </div>
        <div class="col-lg-12">
            <div class="box box-color-1">
                <br>
                <table id="table">
                    <thead>
                    <tr>
                        <th>PMID</th>
                        <th>Gene Name</th>
                        <th>Gene Type</th>
                        <th>Interacting Gene</th>
                        <th>Cell Name</th>
                        <th>Cell Type</th>
                        <th>Tissue Type</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    ini_set("error_reporting", "E_ALL & ~E_NOTICE");
                    include '../public/conn_php.php';
                    $sql = "select * from $main $select_type";
                    $result = mysqli_query($conn, $sql);
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $Interacting_Gene_Symbol = join(', ',preg_split('/;/',$rows["Interacting_Gene_Symbol"]));
                        echo "<tr>";
                        echo "<td><a href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["PMID"]}\">{$rows["PMID"]}</a></td>";
                        echo "<td>{$rows["GeneName"]}</td>";
                        echo "<td>{$rows["GeneType"]}</td>";
                        echo "<td><div class='info-more'>$Interacting_Gene_Symbol<i onclick='infoMore(this)' class=\"ri-add-circle-line\"></i></div></td>";
                        echo "<td>{$rows["CellName"]}</td>";
                        echo "<td>{$rows["CellType"]}</td>";
                        echo "<td>{$rows["TissueType"]}</td>";
                        echo "<td><a target='_blank' href='/$web_title/search/detail_all.php?id={$rows["id"]}'>more detail</a></td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-12">
            <h4>
                Gene count in the tissue and cell selected by the current user.
            </h4>
        </div>
        <div class="col-lg-12">
            <div class="box box-color-2">
                <div id="container" style="height: 100%;width: 100%;display: flex;justify-content: center;align-items:center;"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
            </div>
        </div>


    </div>
</div>

<script>
    $.ajax({
        url: "/<?php echo $web_title ?>/graph/detail_cell_markers.php",
        data: {
            select:"<?php echo urlencode($select_type) ?>"
        },
        type:'post',
        dataType: "json"
    }).then(function (data) {
        window.chart = Highcharts.chart('container', {
            series: [{
                type: 'wordcloud',
                data: data
            }],
            credits: {
                enabled: false
            },
            title: {
                text: null
            }
        });
    })

</script>
<script>
    $('#table').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each((i, e) => {
                switch (i) {
                    case 3:
                        return;
                    default:
                        break
                }
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });
    $(`<i style="font-size: 20px" class="ri-question-line" title="TF-Marker display all the entries about the input based on the filter."></i>`).insertBefore('#table_filter > label')

</script>
</body>
</html>


