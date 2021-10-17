<?php include "../public/public.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
    <style>
        img {
            width: 20px;
            resize: ;
            position: relative;
            top: -1px;
        }
    </style>
</head>
<body>
<?php include "../public/header.php" ?>
<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
include '../public/conn_php.php';
$tis_type = $_GET['tis_type_three'];
$cel_type = $_GET['cel_type_three'];
$tf_name = trim($_REQUEST["tf_name_three"]);
$sql = "select * from $main where GeneName='$tf_name'";
$results = mysqli_query($conn, $sql);
while ($rows = mysqli_fetch_assoc($results)) {
    $Marker[] = $rows['Interacting_Gene_Symbol'];
}
$Marker = join(";", $Marker);
$Markers = preg_split("/;+/i", $Marker);
function rm_null($item){
    return (!empty(trim($item)));
}
$Markers = array_filter($Markers,"rm_null");
$Marker = join(";", array_unique($Markers));

$tf_information_sql = "SELECT *
        from $tf_information
        where tf_information_gene_symbol='" . $tf_name . "'
          ";
$tf_information_res = mysqli_query($conn, $tf_information_sql);
while ($row = mysqli_fetch_assoc($tf_information_res)) {
    $tf_information_ensembl_id = $row["tf_information_ensembl_id"];
    $tf_information_family = $row["tf_information_family"];
    $tf_information_protein = $row["tf_information_protein"];
    $tf_information_entrez_id = $row["tf_information_entrez_id"];
}

$tf_information_protein_tmp = explode(";", $tf_information_protein);
for ($index = 0; $index < count($tf_information_protein_tmp); $index++) {
    $tf_information_protein_final .= "$tf_information_protein_tmp[$index],\n";
}

$min = $_REQUEST["min"];
$max = $_REQUEST["max"];
$Threshold = $_REQUEST["Threshold"];
$databases = join(';', $_REQUEST['databases']);

$alert = "";
if (preg_split("/;/", $databases) < 10) {
    $alert .= "1. There are no enriched pathways from the pathway database(s) user selected. Please choose other ones.";
}
$adjust = $_REQUEST["adjust"] == 'on' ? 1 : 0;
$uniq = [];
?>
<div class="container" style="min-height: 1000px;">
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <div class="pull-right"><i class="ri-map-pin-line"></i>Search / <b
                        class="navigator">Pathway enrichment analysis</b></div>
        </div>
    </div>
    <hr>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3><i class="ri-folder-info-line"></i> TF information</h3>
        </div>
        <div class="panel-body">
            <div class="col-lg-6">
                <div class="box box-color-1">
                    <table class="table table-hover">
                        <tr style="color: red;font-weight:bold;">
                            <td width="40%"><strong>TF name:</strong></td>
                            <td><?php echo $tf_name; ?></td>
                        </tr>
                        <tr>
                            <td><strong>TF family:</strong></td>
                            <td><?php echo $tf_information_family; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ensembl gene ID:</strong></td>
                            <td><?php echo $tf_information_ensembl_id; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ensembl protein ID:</strong></td>
                            <td><?php echo $tf_information_protein_final; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Entrez gene ID:</strong></td>
                            <td><?php echo $tf_information_entrez_id; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tissue Type:</strong></td>
                            <td><?php echo $tis_type; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cell Type:</strong></td>
                            <td><?php echo $cel_type; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Interacting Gene:</strong></td>
                            <?php
                                $gene_names = preg_split("/[;,]+/", $Marker);
                                $sql = "select GeneType,GeneName from $main where
                                TissueType = '$tis_type'
                                and CellType = '$cel_type'
                                and GeneName in ('" . join("','", $gene_names) . "') group by GeneType,GeneName order by GeneName";
                                $result = mysqli_query($conn, $sql);
                                echo "<td style='max-height: 106px;display: block;overflow-y: auto;'><table>";
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td><a onclick='search_detail(`search_by_Marker.php?gene_type={$row["GeneType"]}&gene_name={$row["GeneName"]}`)'>{$row["GeneName"]}</a></td>";
                                    echo "<td>({$row["GeneType"]})</td>";
                                    echo "</tr>";
                                }
                                echo "</table></td>";
                                ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box box-color-2">
                    <div id="enrichment_force_graph" style="width: 100%;height: 300px"></div>
                    <div>
                        <center>
                            <img src="/<?php echo $web_title ?>/public/img/analysis/Pathway_enrichment_analysis_of_genes_result/enrichment_red_ring.png"
                                 alt=" "> is the Interacting Gene
                            <img src="/<?php echo $web_title ?>/public/img/analysis/Pathway_enrichment_analysis_of_genes_result/grey.png"
                                 alt=" "> is the Repression
                            <img src="/<?php echo $web_title ?>/public/img/analysis/Pathway_enrichment_analysis_of_genes_result/yellow.png"
                                 alt=" "> is the activation
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> Pathway enrichment analysis
            </h3>
        </div>

        <div class="panel-body">
            <table style="width:100%;" id="example"
                   class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                <tr>
                    <th>Pathway ID <span class="glyphicon glyphicon-question-sign"
                                         title="Click to view Pathway network on ComPAT web server"></span></th>
                    <th>Pathway name</th>
                    <th>Pathway source</th>
                    <th>Annotated gene</th>
                    <th>Annotated gene number</th>
                    <th>Total gene number</th>
                    <th>TF <span class="glyphicon glyphicon-question-sign"
                                 title="Based on motif change and ChIP-seq data, the TFs from these pathways are variation-associated."></span>
                    </th>
                    <th>TF number</th>
                    <th>P value</th>
                    <th>FDR <span class="glyphicon glyphicon-question-sign"
                                  title="False discovery rate (FDR) : the corrected p-value."></span></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="panel panel-default" id="convert">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> ID convert table
            </h3>
        </div>

        <div class="panel-body">
            <h4><i class="ri-attachment-line" style="font-size: 20px;"></i><b>Users may not input gene symbol. The
                    VARAdb can convert alias; Ensembl ID; NCBI Refseq ID of genes into gene symbol.</b></h4>
            <br>
            <div id="symbol"></div>
        </div>
    </div>

    <?php if ($_REQUEST["type"] == "Gene") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Genes regulated by <?php echo $tf_name; ?>
                </h3>
            </div>
            <div class="panel-body">
                <table id="tf_target_genes" class="table table-hover table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>TF name</th>
                        <th>Gene name</th>
                        <th>Influence_type</th>
                        <th>Pubmed ID</th>
                        <th>Link to pubmed</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tf_micrna_sql = "SELECT *
                        from $tf_mrna_manual
                        where tf_gene_tf_name='$tf_name'";
                    $result = mysqli_query($conn, $tf_micrna_sql);
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $gene_list_start[] = $rows["tf_gene_gene_name"];
                        if (array_key_exists($rows["tf_gene_gene_name"], $uniq)) {
                            if ($uniq[$rows["tf_gene_gene_name"]] == "Unknown") {
                                $uniq[$rows["tf_gene_gene_name"]] = $rows["tf_gene_influence_type"];
                            }
                        } else {
                            $uniq[$rows["tf_gene_gene_name"]] = $rows["tf_gene_influence_type"];
                        }

                        $tf_gene_gene_name = $rows["tf_gene_gene_name"];
                        $tf_gene_tf_name = $rows["tf_gene_tf_name"];
                        $tf_gene_influence_type = $rows["tf_gene_influence_type"];
                        $tf_gene_pubmed = $rows["tf_gene_pubmed"];
                        ?>
                        <tr>
                            <td><?php echo $tf_gene_tf_name; ?></td>
                            <td><?php echo $tf_gene_gene_name; ?></td>
                            <td><?php echo $tf_gene_influence_type; ?></td>
                            <td><?php echo $tf_gene_pubmed; ?></td>
                            <td align="center"><a
                                        href="https://www.ncbi.nlm.nih.gov/pubmed/?term=<?php echo $tf_gene_pubmed; ?>"
                                        target="_blank"><img src="../public/img/pubmed.jpg" width="60px" height="25px"></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div>
                    <hr>
                    <h3><b><font color="red">Red</font></b> is the Interacting Gene.</h3>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($_REQUEST["type"] == "LncRNA") { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> LncRNA regulated by <?php echo $tf_name; ?>
                </h3>
            </div>
            <div class="panel-body">
                <table id="tf_target_genes" class="table table-hover table-bordered table-condensed"
                       cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>TF_name</th>
                        <th>LncRNA_name</th>
                        <th>Gene_name</th>
                        <th>Sample_type</th>
                        <th>Cor_low_group</th>
                        <th>Cor_hight_group</th>
                        <th>LncRNA_modulators</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tf_lncrna_predict_sql = "SELECT *
                        from $tf_lncrna_predict
                        where tf_lncrna_gene_name='$tf_name'";
                    $tf_lncrna_predict_result = mysqli_query($conn, $tf_lncrna_predict_sql);
                    while ($row = mysqli_fetch_assoc($tf_lncrna_predict_result)) {
                        $gene_list_start[] = $row["tf_lncrna_lncrna_name"];
                        $uniq[$row["tf_lncrna_lncrna_name"]] = "";
                        $tf_lncrna_lncrna_name = $row["tf_lncrna_lncrna_name"];
                        $tf_lncrna_tf_name = $row["tf_lncrna_tf_name"];
                        $tf_lncrna_gene_name = $row["tf_lncrna_gene_name"];
                        $tf_lncrna_sample_type = $row["tf_lncrna_sample_type"];
                        $tf_lncrna_cor_low_group = $row["tf_lncrna_cor_low_group"];
                        $tf_lncrna_cor_hight_group = $row["tf_lncrna_cor_hight_group"];
                        $tf_lncrna_lncrna_modulators = $row["tf_lncrna_lncrna_modulators"];
                        ?>
                        <tr>
                            <td><?php echo $tf_lncrna_gene_name; ?></td>
                            <td><?php echo $tf_lncrna_tf_name; ?></td>
                            <td><?php echo $tf_lncrna_lncrna_name; ?></td>
                            <td><?php echo $tf_lncrna_sample_type; ?></td>
                            <td><?php echo $tf_lncrna_cor_low_group; ?></td>
                            <td><?php echo $tf_lncrna_cor_hight_group; ?></td>
                            <td><?php echo $tf_lncrna_lncrna_modulators; ?></td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
<div class="modal fade" id="search_detail_modal" tabindex="-1" role="dialog"
     aria-labelledby="search_detail_modal_label">
    <div class="modal-dialog" style="width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="search_detail_modal_label">Search detail</h4>
            </div>
            <div class="modal-body" id="search_detail_modal_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include "../public/footer.php" ?>
<?php
$gene_list_end = [];
$rs = mysqli_query($conn, "select distinct symbol FROM $idConvert where symbol in  ('" . join("','", $gene_list_start) . "')");
while ($row = mysqli_fetch_assoc($rs)) {
    array_push($gene_list_end, $row["symbol"]);
}

$gene_list_diff = array_diff($gene_list_start, $gene_list_end);
$gene_list = json_encode(trim(join("\t", $gene_list_diff)));


foreach ($uniq as $gene => $in_type) {
    $tf_gene[] = array(
        "gene_name" => $gene,
        "influence_type" => $in_type
    );
}
foreach ($tf_gene as $arr) {
    $tf_gene_gene_name1 = $arr["gene_name"];
    $tf_gene_influence_type1 = $arr["influence_type"];
    if ($tf_gene_gene_name1 == $tf_name) continue;
    $primary_link[] = ['link' => array('val' => $tf_gene_gene_name1, 'info' => $tf_gene_influence_type1)];
    $data .= "{ 
    name: '$tf_gene_gene_name1', 
    category: '{$_REQUEST["type"]}', 
    itemStyle: {
            borderWidth: " . (count(array_intersect($Markers, [$tf_gene_gene_name1])) > 0 ? "5" : "0") . ",
            borderColor: 'red'
        }
    },";
}
foreach ($primary_link as $key => $arrs) {
    foreach ($arrs as $id => $val) {
        $type = 'solid';
        $color = 'green';
        $width = 2;
        switch ($val["info"]) {
            case "Activation":
                $color = 'orange';
                break;
            case "Repression":
                $color = 'gray';
                break;
        }
        $link[] = array(
            "source" => $val["val"],
            "target" => $tf_name,
            "info" => $val["info"],
            "lineStyle" => array(
                "normal" => array(
                    "type" => $type,
                    "color" => $color,
                    "width" => $width
                )
            )
        );
    }
}
$tim = '{ name: "' . $tf_name . '", category:"TF"},';
$data = substr($data, 0, -1);//切割最后一个字符//
$final_data = $tim . $data;
?>
<script>
    console.log(<?php echo $final_data?>);
    console.log(<?php echo json_encode($link)?>);
    var chart = echarts.init(document.getElementById("enrichment_force_graph"));
    var option = {
        title: {
            text: "",
            left: "center",
            top: "5%",
            textStyle: {
                color: '#000'
            }
        },
        legend: {
            data: ["<?php echo $_REQUEST["type"] ?>", "TF"]
        },

        tooltip: {
            formatter: function (obj) {
                console.log(obj);
                if (obj.dataType == "edge") {
                    return '<div style="border-bottom: 1px solid rgba(255,255,255,.3); font-size: 18px;padding-bottom: 7px;margin-bottom: 7px">'
                        + obj.data.info
                        + '</div>'
                } else
                    return '<div style="border-bottom: 1px solid rgba(255,255,255,.3); font-size: 18px;padding-bottom: 7px;margin-bottom: 7px">'
                        + obj.data.category
                        + ':'
                        + obj.name
                        + '</div>'
            }
        },
        animationDuration: 1500,
        animationEasingUpdate: 'quinticInOut',
        series: [
            {
                type: 'graph',
                layout: 'force',
                symbolSize: 30,　//控制球的大小
                force: {
                    repulsion: 60,
                    gravity: 0.0001,
                    edgeLength: [10, 300],
                    layoutAnimation: true,
                },//source表示Ａ，target表示Ｂ，Ａ和Ｂ关联
                data: [<?php echo $final_data;?>],
                links: <?php echo json_encode($link);?>,
                categories: [{
                    'name': '<?php echo $_REQUEST["type"] ?>',　//索引到category后面对应的值
                    itemStyle: {
                        normal: {
                            color: "#337ab7"
                        }
                    }
                },
                    {
                        'name': 'TF',　//索引到category后面对应的值
                        itemStyle: {
                            normal: {
                                color: "red"
                            }
                        }
                    }

                ],
                roam: true,
                draggable: true,
                label: {
                    normal: {
                        formatter: '{b}',
                        show: true,
                        position: 'left', //top
                        fontSize: 10,
                        fontStyle: '0',
                    }
                },
                force: {
                    repulsion: 200,
                    gravity: 0.1,
                    edgeLength: 100,
                    layoutAnimation: true
                },
                lineStyle: {
                    color: 'target',
                    curveness: 0.0
                },
            }]
    };
    chart.setOption(option);
</script>
<script>
    window.table = null;
    var value = <?php echo $gene_list ?>;
    var diff = <?php echo json_encode(join("\n", $gene_list_diff)) ?>;
    console.log(value);

    function getTableContent(n) {
        var rs = [];
        var nTrs = window.table.fnGetNodes();
        for (var i = 0; i < nTrs.length; i++) {
            var t = window.table.fnGetData(nTrs[i]);
            rs.push(t[n]);
        }
        return rs
    }

    if (value) {
        alert("The following ID or alias will be converted to gene symbol:\n" + diff);

        window.table = null;
        $('#symbol').html("<table id=\"symbol_table\" style=\"width: 100%\" class=\"table table-striped table-bordered table-hover table-condensed\">\n" +
            "                <thead>\n" +
            "                <tr>\n" +
            "                    <th>Gene Symbol</th>\n" +
            "                    <th>Entrez Gene ID</th>\n" +
            "                    <th>Also known as</th>\n" +
            "                    <th>Ensembl ID</th>\n" +
            "                    <th>NCBI Refseq ID</th>\n" +
            "                </tr>\n" +
            "                </thead>\n" +
            "            </table>");
        window.table = $('#symbol_table').dataTable({
            dom: '<"top row"<"col-md-6 col-xs-6 pull-left"iB><"col-md-6 col-xs-6 pull-right"f>>rt<"bottom row"<"col-md-4 col-xs-4 pull-left"l><"col-md-8 col-xs-8 pull-right"p>><"clear">',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="ri-folder-download-line" style="font-size: 20px"></i>'
            }],
            scrollX: true,
            ajax: {
                async: false,
                type: "POST",
                url: "id_convert_server.php",
                data: {
                    "params": value,
                    "type": "other2symbol"
                }
            }
        });
        value = getTableContent(0).concat(<?php echo json_encode($gene_list_end) ?>);
    } else {
        $("#convert").hide();
        value = <?php echo json_encode($gene_list_end) ?>;
    }


</script>

<script type="text/javascript">
    var threshold = <?php echo !empty($Threshold) ? $Threshold : 0.05 ?>;
    var database = "<?php echo $databases ?>";
    var adjust = <?php echo !empty($adjust) ? $adjust : 0 ?>;
    var min = <?php echo !empty($min) ? $min : 10 ?>;
    var max = <?php echo !empty($max) ? $max : 500 ?>;

    window.aTable = $("#example").dataTable({
        dom: '<"top row"<"col-md-6 col-xs-6 pull-left"iB><"col-md-6 col-xs-6 pull-right"f>>rt<"bottom row"<"col-md-4 col-xs-4 pull-left"l><"col-md-8 col-xs-8 pull-right"p>><"clear">',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        scrollX: true,
        order: [[6, "desc"]], //默认排序
        ajax: {
            url: 'http://39.98.139.1/BiocApi/kegg',
            async: false,
            type: 'POST',
            data: {
                genes: JSON.stringify(value),
                min: min,
                max: max,
                adjust: adjust,
                Threshold: threshold,
                database: database
            }
        },
        columns: [
            {"data": "pathwayID"},
            {"data": "pathwayName"},
            {"data": "Source"},
            {"data": "AnnGene"},
            {"data": null},
            {"data": "GeneNumber"},
            {"data": "Terminal_TF"},
            {"data": "Total_gene_number"},
            {"data": "PValue"},
            {"data": "FDR"}
        ],
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                var html = '<a href="http://licpathway.net/msg/ComPAT/node2ptsg.do?id=' + row.pathwayID + '&name=' + row.pathwayName + '&&source=' + row.Source + '&&species=' + row.Species + '&annGene=' + row.AnnGene + '&geneNumber=' + row.GeneNumber + '&pValue=' + row.PValue + '&fDR=' + row.FDR + '&tf=' + row.Terminal_TF + '">' + row.pathwayID + '</a>';
                return html;
            }
        }, {
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                return row.AnnGene.split(';').length;
            }
        }],
        createdRow: function (row, data, dataIndex) {
            //console.log(data);
            $(row).children('td').eq(4).attr('id', data.pathwayID);
            if (data.pathwayName.length > 10) {//只有超长，才有td点击事件
                $(row).children('td').eq(1).attr('title', data.pathwayName);
                $(row).children('td').eq(1).css("color", "red");
            }
            if (data.AnnGene)
                if (data.AnnGene.length >= 2) {//只有超长，才有td点击事件
                    $(row).children('td').eq(3).attr('title', data.AnnGene);
                    $(row).children('td').eq(3).css("color", "red");
                }
            if (data.Terminal_TF)
                if (data.Terminal_TF.length > 3) {//只有超长，才有td点击事件
                    $(row).children('td').eq(6).attr('title', data.Terminal_TF);
                    $(row).children('td').eq(6).css("color", "red");
                }
            $(row).children('td').each((i, e) => {
                if (e.innerHTML == '') {
                    $(e).html('\\')
                }
            })
        },
    });
</script>

<script>
    var rs = [];
    var nTrs = window.aTable.fnGetNodes();
    for (var i = 0; i < nTrs.length; i++) {
        var t = window.aTable.fnGetData(nTrs[i]);
        rs.push(t[i]);
    }
    if (rs.length == 0) {
        $.ajax({
            url: 'http://39.98.139.1/BiocApi/kegg',
            async: false,
            type: 'POST',
            data: {
                genes: JSON.stringify(value),
                min: min,
                max: max,
                adjust: 0,
                Threshold: 0.05,
                database: "KEGG;NetPath;Reactome;WikiPathways;PANTHER;PID;HumanCyc;CTD;SMPDB;INOH"
            },
            success: function (d) {
                if (d.recordsTotal != 0) {
                    isreturn = 0;
                    if (database.split(";").length < 20) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: adjust,
                                Threshold: threshold,
                                database: "KEGG;NetPath;Reactome;WikiPathways;PANTHER;PID;HumanCyc;CTD;SMPDB;INOH"
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("There are no enriched pathways from the pathway database(s) user selected. Please choose other ones.\n");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    if (threshold < 0.05) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: adjust,
                                Threshold: 0.05,
                                database: database
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("The threshold (P value) is strict. Please set a new threshold with relaxation.\n");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    if (adjust != 0) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: 0,
                                Threshold: threshold,
                                database: database
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("The threshold (FDR) is strict. Please set a new threshold with relaxation.\n");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    if (database.split(";").length < 20 && threshold < 0.05) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: adjust,
                                Threshold: 0.05,
                                database: "KEGG;NetPath;Reactome;WikiPathways;PANTHER;PID;HumanCyc;CTD;SMPDB;INOH"
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("1. There are no enriched pathways from the pathway database(s) user selected. Please choose other ones.\n2. The threshold (P value) is strict. Please set a new threshold with relaxation.");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    if (database.split(";").length < 20 && adjust != 0) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: 0,
                                Threshold: threshold,
                                database: "KEGG;NetPath;Reactome;WikiPathways;PANTHER;PID;HumanCyc;CTD;SMPDB;INOH"
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("1. There are no enriched pathways from the pathway database(s) user selected. Please choose other ones.\n2. The threshold (FDR) is strict. Please set a new threshold with relaxation.\n");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    if (adjust != 0 && threshold < 0.05) {
                        $.ajax({
                            url: 'http://39.98.139.1/BiocApi/kegg',
                            async: false,
                            type: 'POST',
                            data: {
                                genes: JSON.stringify(value),
                                min: min,
                                max: max,
                                adjust: 0,
                                Threshold: 0.05,
                                database: database
                            },
                            success: function (data) {
                                if (data.recordsTotal != 0) {
                                    alert("1. The threshold (P value) is strict. Please set a new threshold with relaxation.\n2. The threshold (FDR) is strict. Please set a new threshold with relaxation.\n");
                                    isreturn = 1
                                }
                            }
                        });
                    }
                    if (isreturn) return;
                    alert("1. There are no enriched pathways from the pathway database(s) user selected. Please choose other ones.\n2. The threshold (P value) is strict. Please set a new threshold with relaxation.\n3. The threshold (FDR) is strict. Please set a new threshold with relaxation.\n");
                } else {
                    alert("Please input a set of more genes.");
                }
            }
        });
    }
</script>
</body>
<script>
    $('#tf_target_genes').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each((i, e) => {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerHTML === '')
                    $(e).html('\\');
                if (e.innerText.match(/<?php echo join("|",$Markers) ?>/g))
                    $(e).html('<font color="red">'+e.innerText+'</font>');
                $(e).attr('title', e.innerText);
            });
        }
    });
</script>
<script>
    function search_detail(url) {
        $('#search_detail_modal').modal('show');
        $("#search_detail_modal_body").html("");
        $.ajax({
            url: url,
            dataType: "HTML",
            success: function (html) {
                $("#search_detail_modal_body").html(html);
            }
        })
    }
</script>
</html>


