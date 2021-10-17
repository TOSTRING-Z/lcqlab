<?php include(__DIR__ . "/../public/public.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
    <style>
        .box {
            overflow: auto;
        }
    </style>
</head>
<body data-spy="scroll" data-target="#myScrollspy">
<?php include(__DIR__ . "/../public/header.php") ?>
<style>
    .highcharts-figure, .highcharts-data-table table {
        min-width: 310px;
        max-width: 800px;
        margin: 1em auto;
    }

    #container {
        height: 400px;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-lg-12">
        <div class="pull-right"><i class="ri-map-pin-line"></i> Search / <b class="navigator">Detail</b></div>
    </div>
    </div>
    <hr>
    <?php
    ini_set("error_reporting", "E_ALL & ~E_NOTICE");
    include(__DIR__ . "/../public/conn_php.php");
    $id = $_GET['id'];
    $sql = "select PMID,
        group_concat(DISTINCT PMID SEPARATOR ', ') PMID,  
        group_concat(DISTINCT GeneName SEPARATOR ', ') GeneName,  
        group_concat(DISTINCT GeneType SEPARATOR ', ') GeneType,  
        group_concat(DISTINCT ControlMarker SEPARATOR ', ') ControlMarker,  
        group_concat(DISTINCT Interacting_Gene_Symbol SEPARATOR ', ') Interacting_Gene_Symbol,  
        group_concat(DISTINCT CellName SEPARATOR ', ') CellName,  
        group_concat(DISTINCT CellType SEPARATOR ', ') CellType,  
        group_concat(DISTINCT TissueType SEPARATOR ', ') TissueType,  
        group_concat(DISTINCT ExperimentType SEPARATOR ', ') ExperimentType,  
        group_concat(DISTINCT ExperimentalMethod SEPARATOR ', ') ExperimentalMethod,  
        group_concat(DISTINCT Title SEPARATOR ', ') Title,  
        group_concat(DISTINCT Function SEPARATOR ', ') Function,
        group_concat(DISTINCT isTF SEPARATOR ', ') isTF
        from $main
            where id='$id'
            group by id";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($query);
    $PMID = $row["PMID"];
    $GeneName = $row["GeneName"];
    $GeneType = $row["GeneType"];
    $ControlMarker = $row["ControlMarker"];
    $Interacting_Gene_Symbol = $row["Interacting_Gene_Symbol"];
    $CellName = $row["CellName"];
    $CellType = $row["CellType"];
    $TissueType = $row["TissueType"];
    $ExperimentType = $row["ExperimentType"];
    $ExperimentalMethod = $row["ExperimentalMethod"];
    $Credibility = $row["Credibility"];
    $People = $row["People"];
    $Title = $row["Title"];
    $Function = $row["Function"];
    $isTF = $row["isTF"];

    $convert_sql = "select symbol,
        group_concat(DISTINCT ensembl_id SEPARATOR ', ') ensembl_id,  
        group_concat(DISTINCT gene_id SEPARATOR ', ') gene_id,  
        group_concat(DISTINCT accession SEPARATOR ', ') accession
        from $idConvert
            where symbol='$GeneName'
            or alias_symbol='$GeneName'
            group by symbol";
    $query = mysqli_query($conn, $convert_sql);
    $row = mysqli_fetch_assoc($query);
    $symbol = $row["symbol"];
    $ensembl_id = $row["ensembl_id"];
    $gene_id = $row["gene_id"];
    $accession = $row["accession"];

    $info_sql = "select 
    TissueType,
    CellType,
    count(GeneName) number from $main
    where GeneName = '$GeneName'
    group by TissueType,CellType
    order by TissueType,CellType";
    $query = mysqli_query($conn, $info_sql);
    while ($rows = mysqli_fetch_assoc($query)) {
        $data[$rows["CellType"]][] = array(
            "y" => intval($rows["number"]),
            "className" => $rows["CellType"]
        );
        $categories[$rows["TissueType"]] = 1;
    }
    foreach ($data as $k => $v) {
        $series[] = array(
            "name" => $k,
            "data" => $v
        );
    }
    $categories = array_keys($categories);

    $tf_information_sql = "SELECT *
        from $tf_information
        where tf_information_gene_symbol='" . $GeneName . "'
          ";
    $tf_information_res = mysqli_query($conn, $tf_information_sql);
    while ($row = mysqli_fetch_assoc($tf_information_res)) {
        $tf_information_ensembl_id = $row["tf_information_ensembl_id"];
        $tf_information_family = $row["tf_information_family"];
        $tf_information_protein = $row["tf_information_protein"];
        $tf_information_entrez_id = $row["tf_information_entrez_id"];
    }
    $is_tf = boolval(preg_match("/^True$/", trim($isTF))) || (preg_match("/^TF|TF Pmarker$/", trim($GeneType)));
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> Overview
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-color-1">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                            <tr>
                                <td>PMID</td>
                                <td>
                                    <a href="https://pubmed.ncbi.nlm.nih.gov/<?php echo $PMID ?>"><?php echo $PMID ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $is_tf ? "TF" : "Gene" ?>
                                    Name
                                </td>
                                <td><font color="red"><?php echo $GeneName; ?></font></td>
                            </tr>
                            <tr>
                                <td>Entrez gene ID</td>
                                <td><?php echo $gene_id; ?></td>
                            </tr>
                            <tr>
                                <td>Ensembl gene ID</td>
                                <td><?php echo $ensembl_id; ?></td>
                            </tr>
                            <tr>
                                <td>Gene Type
                                </td>
                                <td><?php echo $GeneType; ?></td>
                            </tr>
                            <?php if ($is_tf) { ?>
                                <tr>
                                    <td>TF family</td>
                                    <td><?php echo $tf_information_family ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <?php
                                if (!empty($Interacting_Gene_Symbol)) {
                                    $gene_names = preg_split("/[;,]+/", $Interacting_Gene_Symbol);
                                    //$sql = "select distinct GeneType,GeneName from $main where PMID='$PMID' and GeneName in ('" . join("','", $gene_names) . "')";
                                    echo "<td>Interacting Gene</td>";
                                    echo "<td style='max-height: 106px;display: block;overflow-y: auto;border: none;'><table>";
                                    foreach ($gene_names as $gene_name){
                                        $result = mysqli_query($conn, "select distinct GeneType,GeneName from $main where PMID='$PMID' and GeneName='$gene_name'");
                                        $row = mysqli_fetch_assoc($result);
                                        echo "<tr>";
                                        echo "<td><a onclick='search_detail(`search_by_Marker.php?gene_type={$row["GeneType"]}&gene_name={$gene_name}`)'>{$gene_name}</a></td>";
                                        echo "<td>({$row["GeneType"]})</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table></td>";
                                } elseif(!empty($ControlMarker)) {
                                    ?>
                                    <td>Regulation mode</td>
                                    <td>
                                        <?php echo $ControlMarker ?>
                                    </td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td>Cell Name</td>
                                <td><?php echo $CellName ?></td>
                            </tr>
                            <tr>
                                <td>Cell Type</td>
                                <td><?php echo $CellType ?></td>
                            </tr>
                            <tr>
                                <td>Tissue Type</td>
                                <td><?php echo $TissueType ?></td>
                            </tr>
                            <tr>
                                <td>Experiment Type</td>
                                <td><?php echo $ExperimentType ?></td>
                            </tr>
                            <tr>
                                <td>Experimental name</td>
                                <td><?php echo $ExperimentalMethod ?></td>
                            </tr>
                            <tr>
                                <td>Title</td>
                                <td><?php echo $Title ?></td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap">Description of Gene</td>
                                <td><?php echo $Function ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box box-color-2">
                        <figure class="highcharts-figure">
                            <div id="gene_distribution" style="display: flex;justify-content: center;align-items:center;"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                        </figure>
                        <div class="box">
                            <br>
                            <table id="gene_distribution_table">
                                <thead>
                                <tr>
                                    <th>Cell Name</th>
                                    <th>Cell Type</th>
                                    <th>Tissue Type</th>
                                    <th>PMID</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "select * from $main
                            where GeneName='$GeneName'";
                                $result = mysqli_query($conn, $sql);
                                while ($rows = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$rows["CellName"]}</td>";
                                    echo "<td>{$rows["CellType"]}</td>";
                                    echo "<td>{$rows["TissueType"]}</td>";
                                    echo "<td><a href='https://pubmed.ncbi.nlm.nih.gov/{$rows["PMID"]}'>{$rows["PMID"]}</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($is_tf) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Frequency
                    of <font color="red"><?php echo $GeneName; ?></font> about CRC
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $tf2crc_coltron_bar_sql = "SELECT $sample_id.sample_biosample_id,$data_coltron_tf_num.tf_num_tf_num,$sample_id.sample_coltron_crc_num
                    from $data_coltron_tf_num,$sample_id
                    where tf_num_tf_name='" . $GeneName . "'
                    and $data_coltron_tf_num.tf_num_sample_id=$sample_id.sample_id";
                $tf2crc_coltron_bar_res = mysqli_query($conn, $tf2crc_coltron_bar_sql);
                while ($row = mysqli_fetch_assoc($tf2crc_coltron_bar_res)) {
                    $coltron_biosample_id_list .= "'" . $row["sample_biosample_id"] . "',";
                    $coltron_tf_num_list .= $row["tf_num_tf_num"] . ",";
                    $coltron_crc_num_list .= $row["sample_coltron_crc_num"] . ",";
                }

                $tf2crc_crc_mapper_bar_sql = "SELECT $sample_id.sample_biosample_id,$data_crc_mapper_tf_num.tf_num_tf_num,$sample_id.sample_crc_mapper_crc_num
                    from $data_crc_mapper_tf_num,$sample_id
                    where tf_num_tf_name='$GeneName' 
                    and $sample_id.sample_tissue_type like '%$tis_type%'
                    and $data_crc_mapper_tf_num.tf_num_sample_id=$sample_id.sample_id
                      ";
                $tf2crc_crc_mapper_bar_res = mysqli_query($conn, $tf2crc_crc_mapper_bar_sql);
                while ($row = mysqli_fetch_assoc($tf2crc_crc_mapper_bar_res)) {
                    $crc_mapper_biosample_id_list .= "'" . $row["sample_biosample_id"] . "',";
                    $crc_mapper_tf_num_list .= $row["tf_num_tf_num"] . ",";
                    $crc_mapper_crc_num_list .= $row["sample_crc_mapper_crc_num"] . ",";
                }
                ?>
                <div class="box box-color-2">
                    <div id="tf2crc_bar" style="height: 500px;width: 100%;display: flex;justify-content: center;align-items:center;"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                </div>
                <div class="box box-color-1">
                    <br>
                    <table class="table table-hover table-bordered table-condensed" id="tf_num">
                        <thead>
                        <th>Strategy</th>
                        <th>Biosample type</th>
                        <th>Tissue type</th>
                        <th>Biosample name</th>
                        <th>TF num</th>
                        <th>Detail</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach (["coltron", "crc_mapper"] as $tf_num) {
                            $sql_tf_num = "SELECT tf_num_sample_id,
                                sample_tissue_type,
                                sample_biosample_type,
                                sample_biosample_id,
                                tf_num_tf_num
                                FROM
                                (SELECT tf_num_sample_id,
                                tf_num_tf_num
                                FROM " . sprintf($data__tf_num, $tf_num) . "
                                WHERE tf_num_tf_name = '$GeneName')
                                AS TEM_1
                                JOIN $sample_id
                                ON tf_num_sample_id = sample_id
                                ;";
                            $res_tf_num = mysqli_query($conn, $sql_tf_num);
                            while ($row = mysqli_fetch_assoc($res_tf_num)) {
                                echo "<tr>";
                                echo "<td>$tf_num</td>";
                                echo "<td>{$row["sample_biosample_type"]}</td>";
                                echo "<td>{$row["sample_tissue_type"]}</td>";
                                echo "<td>{$row["sample_biosample_id"]}</td>";
                                echo "<td>{$row["tf_num_tf_num"]}</td>";
                                echo "<td><button type='button' class='btn btn-primary' onclick='crc_detail(`{$row["tf_num_sample_id"]}`,`$tf_num`,`$GeneName`,`$Interacting_Gene_Symbol`)'>detail</button></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var dom = document.getElementById("tf2crc_bar");
            var myChart = echarts.init(dom);
            var app = {};
            option = null;
            option = {
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Frequency of TFs (coltron)', 'All CRC number (coltron)', 'Frequency of TFs (CRC mapper)', 'All CRC number (CRC mapper)']
                },
                grid: {
                    bottom: 200
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: [<?php echo $coltron_biosample_id_list; ?>],
                        axisLabel:
                            {
                                show: true,
                                interval: '0',
                                rotate: 30
                            },
                        name: 'Biosample name',
                        nameLocation: 'center',
                        nameGap: 129,
                        nameTextStyle: {
                            color: "red",
                            fontWeight: "normal",
                            fontSize: "18"
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        name: 'CRC number',
                        nameLocation: 'center',
                        nameGap: 60,
                        nameTextStyle: {
                            color: "red",
                            fontWeight: "normal",
                            fontSize: "18"
                        },
                    }
                ],
                dataZoom: [
                    {
                        show: true,
                        start: 0,
                        end: 30
                    },
                    {
                        type: 'inside',
                        start: 94,
                        end: 100
                    },
                    {
                        show: true,
                        yAxisIndex: 0,
                        filterMode: 'empty',
                        width: 30,
                        height: '80%',
                        showDataShadow: false,
                        left: '93%'
                    }
                ],

                series: [
                    {
                        name: 'Frequency of TFs (coltron)',
                        type: 'bar',
                        data: [<?php  echo $coltron_tf_num_list; ?>],
                        markPoint: {
                            data: [
                                {type: 'max', name: 'Max value'},
                                {type: 'min', name: 'Min value'}
                            ]
                        },
                        markLine: {
                            data: [
                                {type: 'average', name: 'Average value'}
                            ]
                        }
                    },
                    {
                        name: 'All CRC number (coltron)',
                        type: 'bar',
                        data: [<?php echo $coltron_crc_num_list; ?>],
                        markPoint: {
                            data: [
                                {type: 'max', name: 'Max value'},
                                {type: 'min', name: 'Min value'}
                            ]
                        },
                        markLine: {
                            data: [
                                {type: 'average', name: 'Average value'}
                            ]
                        }
                    },
                    {
                        name: 'Frequency of TFs (CRC mapper)',
                        type: 'bar',
                        data: [<?php  echo $crc_mapper_tf_num_list; ?>],
                        markPoint: {
                            data: [
                                {type: 'max', name: 'Max value'},
                                {type: 'min', name: 'Min value'}
                            ]
                        },
                        markLine: {
                            data: [
                                {type: 'average', name: 'Average value'}
                            ]
                        }
                    },
                    {
                        name: 'All CRC number (CRC mapper)',
                        type: 'bar',
                        data: [<?php  echo $crc_mapper_crc_num_list; ?>],
                        markPoint: {
                            data: [
                                {type: 'max', name: 'Max value'},
                                {type: 'min', name: 'Min value'}
                            ]
                        },
                        markLine: {
                            data: [
                                {type: 'average', name: 'Average value'}
                            ]
                        }
                    },
                ],
                color: [
                    '#C1232B', '#B5C334', '#FCCE10', '#27727B',
                    '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',
                    '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'
                ]
            };

            if (option.series[0].data.length > 0) {
                myChart.setOption(option, true);
            } else {
                myChart.showLoading({
                        text: 'No data at present',
                        color: '#ffffff',
                        textColor: '#8a8e91',
                        maskColor: 'rgba(255, 255, 255, 0.8)',
                        fontSize: 60
                    }
                );
            }
        </script>
    <?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> Expression Atlas of <font
                        color="red"><?php echo $GeneName; ?></font>
            </h3>
        </div>
        <div class="panel-body">
            <div style="text-align:center;width:100%;margin:0 auto;">

                <?php echo '<a style="margin-right:30px;" href="search_sample_result_rect/rect_4.php?sample_tf_name=' . $GeneName . '" type="button" class="btn btn-primary" target="sample_result_rect">'; ?>
                <font size="3"><?php echo $Gene_name ?> Expression in Human Normal Tissues (GTEx)</font></a>

                <?php echo '<a style="margin-right:30px;" href="search_sample_result_rect/rect_2.php?sample_tf_name=' . $GeneName . '" type="button" class="btn btn-primary" target="sample_result_rect">'; ?>
                <font size="3"><?php echo $Gene_name ?> Expression in Cancer Cell Lines (CCLE)</font></a></br>
                <div style="height: 10px;"></div>

                <?php echo '<a style="margin-right:30px;" href="search_sample_result_rect/rect_1.php?sample_tf_name=' . $GeneName . '" type="button" class="btn btn-primary" target="sample_result_rect">'; ?>
                <font size="3"><?php echo $Gene_name ?> Expression in Human Cancers (TCGA)</font></a>

                <?php echo '<a style="" href="search_sample_result_rect/rect_3.php?sample_tf_name=' . $GeneName . '" type="button" class="btn btn-primary" target="sample_result_rect">'; ?>
                <font size="3"><?php echo $Gene_name ?> Expression in Encode Cell Lines</font></a>

            </div>
            <div class="embed-responsive" style="height: 600px;">
                <?php echo '<iframe src="search_sample_result_rect/rect_1.php?sample_tf_name=' . $GeneName . '" name="sample_result_rect"
                    onload="changeFrameHeight(this);"
                    scrolling="no"
                    height="100%" width="100%" allowfullscreen="true" allowtransparency="true" frameborder="no"
                    border="0" marginwidth="0" marginheight="0"></iframe>' ?>
            </div>
        </div>
    </div>
    <!--    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> Upstream Pathway Annotation of <font color="red"><?php /*echo $GeneName; */ ?></font>
            </h3>
        </div>
        <div class="panel-body">
            <table id="pathway_table" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>GeneName</th>
                    <th>Pathway Name</th>
                    <th>Source</th>
                    <th>Gene Number</th>
                </tr>
                </thead>
                <tbody>
                <?php
    /*                $pathway_sql = "SELECT *
                              from $pathway
                              where find_in_set('$GeneName',geneset)";
                    $pathway_res = mysqli_query($conn, $pathway_sql);
                    while ($row = mysqli_fetch_assoc($pathway_res)) {
                        $pathway_ID = $row["pathway_ID"];
                        $pathway_name = $row["pathway_name"];
                        $pathway_source = $row["pathway_source"];
                        $gene_number = $row["gene_number"];
                        */ ?>
                    <tr>
                        <td><font color="red"><?php /*echo $GeneName; */ ?></font></td>
                        <td><?php /*echo "<a href='http://www.licpathway.net/msg/ComPAT/node2.do?id=$pathway_ID&name=$pathway_name&source=$pathway_source&species=human&annGene=$GeneName' target='_blank'>$pathway_name</a>"; */ ?></td>
                        <td><?php /*echo $pathway_source; */ ?></td>
                        <td><?php /*echo $gene_number; */ ?></td>
                    </tr>
                <?php /*} */ ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i>  Enhancer information of <font color="red"><?php /*echo $GeneName; */ ?></font>
            </h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" id="Enhancer_table">
                <thead>
                <tr>
                    <th>Enhancer ID</th>
                    <th>Enhancer symbol</th>
                    <th>Genome location</th>
                    <th>Biosample name</th>
                    <th>Disease</th>
                    <th>Experiment</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i>  Variation information of <font color="red"><?php /*echo $GeneName; */ ?></font>
            </h3>
        </div>
        <div class="panel-body">
            <a href="search_result_detail_snp_related_information_eqtl.php?GeneName=<?php /*echo $GeneName */ ?>"
               target="detail_snp_related_ifr" class="btn btn-primary">eQTL</a>
            <a href="search_result_detail_snp_related_information_risk_snp.php?GeneName=<?php /*echo $GeneName */ ?>"
               target="detail_snp_related_ifr" class="btn btn-primary">Risk SNP</a>
            <a href="search_result_detail_snp_related_information_somatic_mutation.php?GeneName=<?php /*echo $GeneName */ ?>"
               target="detail_snp_related_ifr" class="btn btn-primary">Somatic mutation</a>
            <hr>
            <iframe name=detail_snp_related_ifr
                    src="search_result_detail_snp_related_information_eqtl.php?GeneName=<?php /*echo $GeneName */ ?>"
                    onload="changeFrameHeight(this);"
                    scrolling="no"
                    height="100%" width="100%" allowfullscreen="true" allowtransparency="true" frameborder="no"
                    border="0" marginwidth="0" marginheight="0"></iframe>
        </div>
    </div>-->

</div>
<div id="loading" class="loading">
    <i class="ri-loader-2-line rotateIn"></i>
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
<?php include '../public/footer.php' ?>
<script>

    $('#gene_distribution_table').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        lengthMenu: [5, 10, 50, 100],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each(function (i, e) {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });

    $('#tf_num').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each(function (i, e) {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });
    $('#pathway_table').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each(function (i, e) {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });

    $('#Enhancer_table').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        ajax: {
            async: true,
            type: "POST",
            url: "search_enhaner_server.php",
            data: {
                "GeneName": "<?php echo $GeneName?>"
            }
        },
        columnDefs: [{
            targets: 0,
            data: null,
            render: function (data, type, row) {
                var html = '<a target="_blank" href="/ENdb/search/Detail.php?Species=Human&Enhancer_id=' + row[0] + '">' + row[0] + '</a>';
                return html;
            }
        }],
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each(function (i, e) {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerText === '--')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });

    Highcharts.chart('gene_distribution', {
        chart: {
            type: 'bar'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Frequency of <?php echo $GeneName?> about <?php echo $web_title?>'
        },
        xAxis: {
            categories: <?php echo json_encode($categories) ?>
        },
        yAxis: {
            min: 0,
            allowDecimals: false,
            title: {
                text: ''
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: <?php echo json_encode($series) ?>
    });
</script>
</body>

</html>
