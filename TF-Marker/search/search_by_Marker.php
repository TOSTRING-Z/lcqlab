<?php include "../public/public.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body>
<div class="container-fluid" id="body">
    <?php
    include '../public/conn_php.php';
    $gene_data = get_symbol($_GET["gene_name"]);
    if (is_null($gene_data)){
        $Gene_name = $_GET["gene_name"];
    } else {
        $Gene_name = $gene_data[0][0];
        echo '
        <div class="col-lg-12">
            <h4>ID convert table</h4>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-color-1">
                <br>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Gene Symbol</th>
                            <th>Alias</th>
                            <th>Ensembl ID</th>
                            <th>Entrez Gene ID</th>
                            <th>NCBI Refseq ID</th>
                        </tr>
                        </thead>
                        <tbody>';
        foreach ($gene_data as $row) {
            echo "<tr>
                    <td>{$row[0]}</td>
                    <td>{$row[1]}</td>
                    <td>{$row[2]}</td>
                    <td>{$row[3]}</td>
                    <td>{$row[4]}</td>
                <tr/>";
        }
        echo "
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            alert(`The following ID or alias will be converted to gene symbol:\n{$_GET["gene_name"]} --> $Gene_name`);
        </script>
        ";
    }

    $Gene_Type = $_GET["gene_type"];
    function rm_null($item)
    {
        return (!empty($item));
    }

    $select_type = array_filter([
        empty($Gene_name) ? null : "GeneName='$Gene_name'",
        empty($Gene_Type) ? null : "GeneType='$Gene_Type'"
    ], "rm_null");
    if (count($select_type) > 0)
        $select_type = "where " . join(" and ", $select_type);
    else
        $select_type = "";
    ?>
    <div class="row">
        <div class="col-lg-12">
            <h4>Currently,
                the gene type selected by the user is <b><font
                            color="red"><?php echo empty($Gene_Type) ? "all" : $Gene_Type ?></font></b>,
                and the gene name is <b><font
                            color="red"><?php echo empty($Gene_name) ? "all" : $Gene_name ?></font></b>.
            </h4>
        </div>
        <div class="col-lg-12">
            <div class="box box-color-1">
                <br>
                <table id="table">
                    <thead>
                    <tr>
                        <th>Gene Name</th>
                        <th>Gene Type</th>
                        <th>Cell Name</th>
                        <th>Cell Type</th>
                        <th>Tissue Type</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "select * from $main $select_type";
                    $result = mysqli_query($conn, $sql);
                    while ($rows = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$rows["GeneName"]}</td>";
                        echo "<td>{$rows["GeneType"]}</td>";
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
        <?php if (!empty($Gene_name)) { ?>
            <div class="col-lg-12">
                <div class="box box-color-2">
                    <div style="text-align:center;width:100%;margin:0 auto;">

                        <?php echo '<a style="margin-right:30px;" href="/' . $web_title . '/search/search_sample_result_rect/rect_4.php?sample_tf_name=' . $Gene_name . '" type="button" class="btn btn-info" target="sample_result_rect_modal">'; ?>
                        <font size="3"><?php echo $Gene_name ?> Expression in Human Normal Tissues (GTEx)</font></a>

                        <?php echo '<a style="margin-right:30px;" href="/' . $web_title . '/search/search_sample_result_rect/rect_2.php?sample_tf_name=' . $Gene_name . '" type="button" class="btn btn-info" target="sample_result_rect_modal">'; ?>
                        <font size="3"><?php echo $Gene_name ?> Expression in Cancer Cell Lines (CCLE)</font></a></br>
                        <div style="height: 10px;"></div>

                        <?php echo '<a style="margin-right:30px;" href="/' . $web_title . '/search/search_sample_result_rect/rect_1.php?sample_tf_name=' . $Gene_name . '" type="button" class="btn btn-info" target="sample_result_rect_modal">'; ?>
                        <font size="3"><?php echo $Gene_name ?> Expression in Human Cancers (TCGA)</font></a>

                        <?php echo '<a style="" href="/' . $web_title . '/search/search_sample_result_rect/rect_3.php?sample_tf_name=' . $Gene_name . '" type="button" class="btn btn-info" target="sample_result_rect_modal">'; ?>
                        <font size="3"><?php echo $Gene_name ?> Expression in Encode Cell Lines</font></a>

                    </div>
                    <div class="embed-responsive" style="height: 600px;">
                        <?php echo '<iframe src="/' . $web_title . '/search/search_sample_result_rect/rect_1.php?sample_tf_name=' . $Gene_name . '" name="sample_result_rect_modal"
                    onload="changeFrameHeight(this);"
                    scrolling="no"
                    height="100%" width="100%" allowfullscreen="true" allowtransparency="true" frameborder="no"
                    border="0" marginwidth="0" marginheight="0"></iframe>' ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-lg-12">
            <h4>
                Distribution of <b><font color="red"><?php echo empty($Gene_name) ? "gene" : $Gene_name ?></font></b> in
                tissues and cells.
            </h4>
        </div>
        <div class="col-lg-12">
            <div class="box box-color-2">
                <div id="main"
                     style="width: 100%;height: 800px;display: flex;justify-content: center;align-items:center;"><i
                            style="width: 26px;height: 38px;"
                            class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
            </div>
        </div>
    </div>
</div>

<?php
$info_sql = "select CellType,
    TissueType,
    group_concat(distinct GeneType) GeneType,
    substring_index(group_concat(distinct GeneName),',',1) GeneName,
    count(id) number
     from $main
    $select_type
    group by  CellType,TissueType";
$results = mysqli_query($conn, $info_sql);
while ($rows = mysqli_fetch_assoc($results)) {
    if (is_null($TissueType[$rows["TissueType"]]) || is_null($CellType[$rows["CellType"]]))
        $data_primary[] = array($rows["CellType"], $rows["TissueType"], intval($rows["number"]), $rows["GeneType"], $rows["GeneName"]);
    if (is_null($CellType[$rows["CellType"]]))
        $CellType[$rows["CellType"]] = count(array_keys($CellType));
    if (is_null($TissueType[$rows["TissueType"]]))
        $TissueType[$rows["TissueType"]] = count(array_keys($TissueType));
}
foreach ($data_primary as $rows) {
    $data[] = array($CellType[$rows[0]], $TissueType[$rows[1]], $rows[2], $rows[3], $rows[4]);
}
$CellType = array_keys($CellType);
$TissueType = array_keys($TissueType);
?>
<script>
    setTimeout(function (e) {
        var chartDom = document.getElementById('main');
        var myChart = echarts.init(chartDom);
        var option;

        var CellType = <?php echo json_encode($CellType) ?>;
        var TissueType = <?php echo json_encode($TissueType) ?>;
        var data = <?php echo json_encode($data) ?>;
        console.log([CellType, TissueType, data]);

        option = {
            legend: {
                tooltip: {
                    show: true
                },
                left: 'center'
            },
            tooltip: {
                position: 'top',
                formatter: function (params) {
                    console.log(params);
                    return '<div style="font-size: 18px;padding-bottom: 7px;margin-bottom: 7px"">'
                        + 'GeneType: ' + '<b>' + '<font color="red">' + params.value[3] + '</b>' + '</font>' + '<br>'
                        + 'GeneName: ' + '<b>' + '<font color="red">' + params.value[4] + '</b>' + '</font>' + '<br>'
                        + 'Number: ' + '<b>' + '<font color="red">' + params.value[2] + '</b>' + '</font>'
                        + '<br>'
                        + 'TissueType: ' + '<b>' + '<font color="red">' + TissueType[params.value[1]] + '</b>' + '</font>'
                        + '<br>'
                        + 'CellType: ' + '<b>' + '<font color="red">' + CellType[params.value[0]] + '</b>' + '</font>'
                        + '</div>'
                },
                axisPointer: {
                    type: 'cross'
                }
            },
            grid: {
                left: "10%",
                bottom: "10%",
                right: "10%",
                top: "10%",
                containLabel: true,
                show: true
            },
            xAxis: {
                type: 'category',
                data: CellType,
                boundaryGap: true,
                splitLine: {
                    show: true
                },
                axisLine: {
                    show: true
                }
            },
            yAxis: {
                type: 'category',
                data: TissueType,
                axisLine: {
                    show: true
                }
            },
            series: [{
                name: '<?php echo $Gene_Type ?>',
                type: 'scatter',
                symbolSize: function (val) {
                    return Math.log2(val[2])*4 + 10;
                },
                data: data,
                animationDelay: function (idx) {
                    return idx * 5;
                }
            }]
        };

        option && myChart.setOption(option);
    }, 1000)

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

