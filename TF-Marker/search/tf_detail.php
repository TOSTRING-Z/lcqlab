<?php include(__DIR__ . "/../public/public.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
    <style>
        .panel .panel-heading > h3 {
            display: inline-block;
        }

        button.btn:nth-child(2) {
            float: right;
        }

        a.btn-success:nth-child(1) {
            background-color: #5cb85c;
            border-color: #fcfcfc;
        }

        a.btn-success:nth-child(2) {
            background-color: #5cb85c;
            border-color: #fcfcfc;
        }

        .col-lg-7 > a:nth-child(3) {
            background-color: #337ab7;
            border-color: #fcfcfc;
        }

    </style>
</head>
<body>
<?php include(__DIR__ . "/../public/header.php") ?>

<div class="container">

    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <div class="pull-right"><i class="ri-map-pin-line"></i> Analysis / <b class="navigator">TF detail</b></div>
        </div>
    </div>
    <hr>

    <?php
    include '../public/conn_php.php';
    $gene_data = get_symbol($_GET["tf_name"]);
    if (is_null($gene_data)){
        $tf_name = $_GET["tf_name"];
    } else {
        $tf_name = $gene_data[0][0];
        echo '
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>
                        <i class="ri-folder-info-line"></i> ID convert table
                    </h3>
                </div>
                <div class="panel-body">
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
        </div>
        <script>
            alert(`The following ID or alias will be converted to gene symbol:\n{$_GET["tf_name"]} --> $tf_name`);
        </script>
        ";
    }
    $tis_type = $_GET["tis_type_two"];
    $sql = "select * from $main where GeneName='$tf_name'";
    $results = mysqli_query($conn, $sql);
    while ($rows = mysqli_fetch_assoc($results)) {
        $Marker[] = $rows['Interacting_Gene_Symbol'];
    }
    $Marker = join(";", $Marker);
    $Markers = preg_split("/;+/i", $Marker);
    $Marker = join(";", array_unique($Markers));
    $search_gene_select_gene_options = $_GET["search_gene_select_gene_options"];

    $search_gene_tf_name = $_GET["search_gene_tf_name"];

    $search_gene_gene_type = $_GET["search_gene_gene_type"];
    $search_gene_input_gene = $_GET["search_gene_input_gene"];

    if ($search_gene_select_gene_options == 'select_gene_options_1') {
        $tf_information_sql = "SELECT tf_information_gene_symbol
        from $tf_information
        where tf_information_gene_symbol='$search_gene_tf_name'
          ";

        $tf_information_res = mysqli_query($conn, $tf_information_sql);
        while ($row = mysqli_fetch_assoc($tf_information_res)) {
            $tf_name = $row["tf_information_gene_symbol"];
        }

    }

    if ($search_gene_select_gene_options == 'select_gene_options_2') {
        $tf_information_sql = "SELECT tf_information_gene_symbol
        from $tf_information
        where $search_gene_gene_type='" . $search_gene_input_gene . "'
          ";

        $tf_information_res = mysqli_query($conn, $tf_information_sql);
        while ($row = mysqli_fetch_assoc($tf_information_res)) {
            $tf_name = $row["tf_information_gene_symbol"];
        }
    }
    $tf_information_sql = "SELECT *
        from $tf_information
        where tf_information_gene_symbol='$tf_name'
          ";

    $tf_information_res = mysqli_query($conn, $tf_information_sql);
    while ($row = mysqli_fetch_assoc($tf_information_res)) {
        $tf_information_gene_symbol = $row["tf_information_gene_symbol"];
        $tf_information_ensembl_id = $row["tf_information_ensembl_id"];
        $tf_information_family = $row["tf_information_family"];
        $tf_information_protein = $row["tf_information_protein"];
        $tf_information_entrez_id = $row["tf_information_entrez_id"];
    }

    $tf_information_protein_tmp = explode(";", $tf_information_protein);
    for ($index = 0; $index < count($tf_information_protein_tmp); $index++) {
        $tf_information_protein_final .= "$tf_information_protein_tmp[$index],\n";
    }
    ?>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> TF information
                </h3>
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
                                <td><strong>Interacting Gene:</strong></td>
                                <?php
                                $gene_names = preg_split("/[;,]+/", $Marker);
                                $sql = "select GeneType,GeneName from $main where
                                TissueType = '$tis_type'
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
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td width="25%"><strong>Database name</strong></td>
                                <td width="25%"><strong>Link</strong></td>
                                <td width="25%"><strong>Database name</strong></td>
                                <td width="25%"><strong>Link</strong></td>
                            </tr>
                            <tr>
                                <td><strong>NCBI Gene:</strong></td>
                                <td>
                                    <a href="https://www.ncbi.nlm.nih.gov/gene/?term=<?php echo $tf_information_gene_symbol; ?>"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/ncbi.png"
                                                           height="25"
                                                           width="80"></a></td>
                                <td><strong>Genecards:</strong></td>
                                <td>
                                    <a href="http://www.genecards.org/cgi-bin/carddisp.pl?gene=<?php echo $tf_information_gene_symbol; ?>"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/genecard.gif"
                                                           height="25"
                                                           width="80"></a></td>
                            </tr>
                            <tr>
                                <td><strong>Uniprot:</strong></td>
                                <td>
                                    <a href="http://uniprot.org/uniprot/?query=<?php echo $tf_information_gene_symbol; ?>_HUMAN"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/uniport.png"
                                                           height="25" width="80"></a>
                                </td>
                                <td><strong>Wikipedia:</strong></td>
                                <td><a href="http://en.wikipedia.org/wiki/<?php echo $tf_information_gene_symbol; ?>"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/wiki.png"
                                                           height="25"
                                                           width="80"></a></td>
                            </tr>
                            <tr>
                                <td><strong>Cosmic 3D:</strong></td>
                                <td>
                                    <a href="https://cancer.sanger.ac.uk/cosmic3d/protein/<?php echo $tf_information_gene_symbol; ?>"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/cosmic3d.png"
                                                           height="25"
                                                           width="80"></a></td>
                                <td><strong>Geneontology:</strong></td>
                                <td>
                                    <a href="http://amigo.geneontology.org/amigo/medial_search?q=<?php echo $tf_information_gene_symbol; ?>&searchtype=all"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/go.png" height="25"
                                                           width="80"></a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>CCLE:</strong></td>
                                <td>
                                    <a href="https://portals.broadinstitute.org/ccle/page?gene=<?php echo $tf_information_gene_symbol; ?>"
                                       target="blank"><img src="/<?php echo $web_title ?>/public/img/ccle.png"
                                                           height="25"
                                                           width="80"></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Frequency
                    of <font color="red"><?php echo $tf_information_gene_symbol; ?></font> in the <font
                            color="red"><?php echo $tis_type ?></font> about CRC
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $tf2crc_coltron_bar_sql = "SELECT $sample_id.sample_biosample_id,$data_coltron_tf_num.tf_num_tf_num,$sample_id.sample_coltron_crc_num
                    from $data_coltron_tf_num,$sample_id
                    where tf_num_tf_name='" . $tf_name . "'
                    and $sample_id.sample_tissue_type like '%$tis_type%'
                    and $data_coltron_tf_num.tf_num_sample_id=$sample_id.sample_id";
                $tf2crc_coltron_bar_res = mysqli_query($conn, $tf2crc_coltron_bar_sql);
                while ($row = mysqli_fetch_assoc($tf2crc_coltron_bar_res)) {
                    $coltron_biosample_id_list .= "'" . $row["sample_biosample_id"] . "',";
                    $coltron_tf_num_list .= $row["tf_num_tf_num"] . ",";
                    $coltron_crc_num_list .= $row["sample_coltron_crc_num"] . ",";
                }

                $tf2crc_crc_mapper_bar_sql = "SELECT $sample_id.sample_biosample_id,$data_crc_mapper_tf_num.tf_num_tf_num,$sample_id.sample_crc_mapper_crc_num
                    from $data_crc_mapper_tf_num,$sample_id
                    where tf_num_tf_name='$tf_name' 
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
                    <div id="tf2crc_bar"
                         style="height: 500px;width: 100%;display: flex;justify-content: center;align-items:center;"><i
                                style="width: 26px;height: 38px;"
                                class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
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
                        foreach (["coltron", "crc_mapper"] as $first_crc) {
                            $sql_tf_num = "SELECT tf_num_sample_id,
                                sample_tissue_type,
                                sample_biosample_type,
                                sample_biosample_id,
                                tf_num_tf_num
                                FROM
                                (SELECT tf_num_sample_id,
                                tf_num_tf_num
                                FROM " . sprintf($data__tf_num, $first_crc) . "
                                WHERE tf_num_tf_name = '$tf_name')
                                AS TEM_1
                                JOIN $sample_id
                                ON tf_num_sample_id = sample_id
                                and $sample_id.sample_tissue_type like '%$tis_type%';";
                            $res_tf_num = mysqli_query($conn, $sql_tf_num);
                            while ($row = mysqli_fetch_assoc($res_tf_num)) {
                                echo "<tr>";
                                echo "<td>$first_crc</td>";
                                echo "<td>{$row["sample_biosample_type"]}</td>";
                                echo "<td>{$row["sample_tissue_type"]}</td>";
                                echo "<td>{$row["sample_biosample_id"]}</td>";
                                echo "<td>{$row["tf_num_tf_num"]}</td>";
                                echo "<td><button type='button' class='btn btn-primary' onclick='crc_detail(`{$row["tf_num_sample_id"]}`,`$first_crc`,`$tf_name`,`$Marker`)'>detail</button></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Frequency
                    of <font color="red"><?php echo $tf_information_gene_symbol; ?></font> in the <font
                            color="red"><?php echo $tis_type ?></font> about TF-Marker
                </h3>
            </div>
            <div class="panel-body">
                <?php
                $info_sql = "select 
                    TissueType,
                    CellType,
                    count(GeneName) number from $main
                    where GeneName = '$tf_name'
                    and TissueType like '%$tis_type%'
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
                ?>
                <div class="box box-color-2">
                    <figure class="highcharts-figure">
                        <div id="gene_distribution" style="display: flex;justify-content: center;align-items:center;"><i
                                    style="width: 26px;height: 38px;"
                                    class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                    </figure>
                </div>
                <div class="box box-color-1">
                    <br>
                    <table class="table table-hover table-bordered table-condensed" id="gene_distribution_table">
                        <thead>
                        <th>TissueType</th>
                        <th>CellType</th>
                        <th>GeneName</th>
                        </thead>
                        <tbody>
                        <?php
                        $info_sql = "select 
                        TissueType,
                        CellType,
                        GeneName from $main
                        where GeneName = '$tf_name'
                        and TissueType like '%$tis_type%'";
                        $query = mysqli_query($conn, $info_sql);
                        while ($rows = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td>{$rows["TissueType"]}</td>";
                            echo "<td>{$rows["CellType"]}</td>";
                            echo "<td>{$rows["GeneName"]}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> <font
                            color="red"><?php echo $tf_information_gene_symbol; ?></font> distribution of the most
                    representative CRC in the <font
                            color="red"><?php echo $tis_type ?></font>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-hover table-bordered table-condensed" cellspacing="0" id="first_crc">
                    <thead>
                    <th>Strategy</th>
                    <th>Tissue type</th>
                    <th>Biosample type</th>
                    <th>Biosample name</th>
                    <th>Detail</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach (["coltron", "crc_mapper"] as $first_crc) {
                        $sql_first_crc = "SELECT first_crc_sample_id,
                                sample_tissue_type,
                                sample_biosample_type,
                                sample_biosample_id
                                FROM
                                (SELECT first_crc_sample_id
                                FROM " . sprintf($data__first_crc, $first_crc) . "
                                WHERE FIND_IN_SET('$tf_name',first_crc_crc))
                                AS TEM
                                JOIN $sample_id
                                ON first_crc_sample_id = sample_id
                                and $sample_id.sample_tissue_type like '%$tis_type%'";
                        $res_first_crc = mysqli_query($conn, $sql_first_crc);
                        while ($row = mysqli_fetch_assoc($res_first_crc)) {
                            echo "<tr>";
                            echo "<td>$first_crc</td>";
                            echo "<td>{$row["sample_tissue_type"]}</td>";
                            echo "<td>{$row["sample_biosample_type"]}</td>";
                            echo "<td>{$row["sample_biosample_id"]}</td>";
                            echo "<td><button type='button' class='btn btn-primary' onclick='crc_detail(`{$row["first_crc_sample_id"]}`,`$first_crc`,`$tf_name`,`$Marker`)'>detail</button></td>";
                            echo "</tr>";
                            if (empty($all_crc_view_sample))
                                $all_crc_view_sample = $row["first_crc_sample_id"];
                        }


                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Genomic
                    distribution of SEs associated with <font
                            color="red"> <?php echo $tf_information_gene_symbol; ?></font> in the <font
                            color="red"><?php echo $tis_type ?></font>
                </h3>
                <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#se_genome_regions_help">
                    Help
                </button>
            </div>
            <div class="panel-body">
                <?php
                $gene_regional_location_sql = "SELECT * FROM
              (SELECT
                tf2se_tf_name,
                sample_tissue_type,
                sample_biosample_id,
                sample_id,
                tf2se_se_chr,
                tf2se_se_start,
                tf2se_se_end,
                tf2se_se_score
              FROM
                $data_coltron_tf2se,
                $sample_id
              WHERE
                tf2se_tf_name = '$tf_name'
                and $sample_id.sample_tissue_type like '%$tis_type%'
              AND $data_coltron_tf2se.tf2se_sample_id = $sample_id.sample_id
              ORDER BY
                tf2se_se_start ASC)
                AS TEM ORDER BY TEM.tf2se_se_score ASC";
                $gene_regional_location_res = mysqli_query($conn, $gene_regional_location_sql);
                $i_len = 0;
                $min = 0;
                $max = 0;
                while ($row = mysqli_fetch_assoc($gene_regional_location_res)) {
                    $start = floatval($row["tf2se_se_start"]);
                    $end = floatval($row["tf2se_se_end"]);
                    if (empty($max)) $max = $end;
                    if (empty($min)) $min = $start;
                    if ($max < $end) $max = $end;
                    if ($min > $start) $min = $start;
                    $len = $end - $start;
                    $data["len"][$i_len] = $len;
                    $data["tf2se_se_start"][$i_len] = $start;
                    $data["tf2se_se_end"][$i_len] = $end;
                    $data["sample_id"][$i_len] = $row["sample_id"];
                    $data["sample_tissue_type"][$i_len] = $row["sample_tissue_type"];
                    $data["sample_biosample_id"][$i_len] = $row["sample_biosample_id"];
                    $data["tf2se_se_chr"][$i_len] = $row["tf2se_se_chr"];
                    $data["tf2se_se_score"][$i_len] = $row["tf2se_se_score"];
                    $i_len++;
                }
                $start_json = json_encode($data);
                ?>
                <div id="gene_regional_location"
                     style="height: 500px;width: 100%;display: flex;justify-content: center;align-items:center;"><i
                            style="width: 26px;height: 38px;"
                            class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="se_genome_regions_help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Genomic distribution of SEs associated with TF</h4>
                </div>
                <div class="modal-body">
                    <center><img
                                src="/<?php echo $web_title ?>/public/img/tf_detail_page_SEs_in_chromatin_distribution_help.png"
                                width="400px" height="200px"></center>
                    <div style="padding-left: 8%;padding-right: 8%;">
                        <hr>
                        <div style="color:red;font-size: 130%">Figure legend:</div>
                        <div style="color:#000000;font-size: 110%;text-align: justify;margin-bottom: 10px">
                            Genomic distribution of SEs associated with TF: It can roughly understand the genomic
                            regions of SEs associated with TF, and help to carry out the next analysis, such as knocking
                            down the experiment.
                        </div>
                        <div style="color:red;font-size: 130%">What is important information?</div>
                        <div style="color:#000000;font-size: 110%;text-align: justify;margin-bottom: 10px">
                            The yellow box can be thought of as the common genomic regions that regulates the SEs
                            associated with TF. For example, if a knockdown experiment is performed on this region, then
                            theoretically, the expression of the TF will be greatly reduced.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Downstream regulation of <font
                            color="red"><?php echo $tf_information_gene_symbol; ?></font>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-7">
                        <?php echo '<a href="/' . $web_title . '/search/tf_mrna_manual.php?tf_name=' . $tf_name . '" type="button" class="btn btn-success" target="down_iframe">'; ?>
                        Gene</a>
                        <?php echo '<a href="/' . $web_title . '/search/tf_microrna_manual.php?tf_name=' . $tf_name . '" type="button" class="btn btn-success" target="down_iframe"">'; ?>
                        MicroRNA</a>
                        <?php echo '<a href="/' . $web_title . '/search/tf_lncrna_predict.php?tf_name=' . $tf_name . '" type="button" class="btn btn-primary" target="down_iframe"">'; ?>
                        LncRNA</a>
                    </div>
                    <div class="col-lg-5">
                        <div class="col-lg-6" style="background-color:#5cb85c;color:#ffffff">Experimental
                            confirmation
                        </div>
                        <div class="col-lg-6" style="background-color:#337ab7;color:#ffffff">High throughput
                            prediction
                        </div>
                    </div>

                </div>
                <?php echo '<iframe onload="changeFrameHeight(this);" src="/' . $web_title . '/search/tf_mrna_manual.php?tf_name=' . $tf_name . '"  name="down_iframe"
                    scrolling="no"
                    height="100%" width="100%" allowfullscreen="true" allowtransparency="true" frameborder="no"
                    border="0" marginwidth="0" marginheight="0"></iframe>' ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Upstream Pathway Annotation of <font
                            color="red"><?php echo $tf_name; ?></font>
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
                    $pathway_sql = "SELECT *
                                          from $pathway
                                          where find_in_set('$tf_name',geneset)";
                    $pathway_res = mysqli_query($conn, $pathway_sql);
                    while ($row = mysqli_fetch_assoc($pathway_res)) {
                        $pathway_ID = $row["pathway_ID"];
                        $pathway_name = $row["pathway_name"];
                        $pathway_source = $row["pathway_source"];
                        $gene_number = $row["gene_number"];
                        ?>
                        <tr>
                            <td><font color="red"><?php echo $tf_name; ?></font></td>
                            <td><?php echo "<a href='http://www.licpathway.net/msg/ComPAT/node2.do?id=$pathway_ID&name=$pathway_name&source=$pathway_source&species=human&annGene=$tf_name' target='_blank'>$pathway_name</a>"; ?></td>
                            <td><?php echo $pathway_source; ?></td>
                            <td><?php echo $gene_number; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Mutation of <font
                            color="red"> <?php echo $tf_information_gene_symbol; ?></font>
                </h3>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="box box-color-1">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="70%"><strong>Database name</strong></th>
                                    <th width="30%">Link</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><strong>gnomAD:</strong></td>
                                    <td>
                                        <a href="http://gnomad-old.broadinstitute.org/gene/<?php echo $tf_information_ensembl_id; ?>"
                                           target="blank">gnomAD</a></td>
                                </tr>
                                <tr>
                                    <td><strong>ExAC:</strong></td>
                                    <td>
                                        <a href="http://exac.broadinstitute.org/gene/<?php echo $tf_information_ensembl_id; ?>"
                                           target="blank">ExAC</a></td>
                                </tr>
                                <tr>
                                    <td><strong>ICGC:</strong></td>
                                    <td><a href="https://dcc.icgc.org/genes/<?php echo $tf_information_ensembl_id; ?>"
                                           target="blank"><img src="/<?php echo $web_title ?>/public/img/icgc.png"
                                                               height="50"
                                                               width="50"></a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="box box-color-2">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="70%"><strong>Database name</strong></th>
                                    <th width="30%">Link</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><strong>Cosmic:</strong></td>
                                    <td>
                                        <a href="https://cancer.sanger.ac.uk/cosmic/search?q=<?php echo $tf_information_gene_symbol; ?>"
                                           target="blank"><img src="/<?php echo $web_title ?>/public/img/cosmic.png"
                                                               height="25"
                                                               width="80"></a></td>
                                </tr>
                                <tr>
                                    <td><strong>Cosmic cell lines:</strong></td>
                                    <td>
                                        <a href="https://cancer.sanger.ac.uk/cell_lines/gene/analysis?ln=<?php echo $tf_information_gene_symbol; ?>"
                                           target="blank"><img
                                                    src="/<?php echo $web_title ?>/public/img/cosmic_cellline.png"
                                                    height="25"
                                                    width="80"></a></td>
                                </tr>
                                <tr>
                                    <td><strong>Depmap:</strong></td>
                                    <td>
                                        <a href="https://depmap.org/portal/gene/<?php echo $tf_information_gene_symbol; ?>?tab=overview"
                                           target="blank"><img src="/<?php echo $web_title ?>/public/img/demap.png"
                                                               height="25"
                                                               width="80"></a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Disease information of <font
                            color="red"><?php echo $tf_information_gene_symbol; ?></font>
                </h3>
            </div>
            <div class="panel-body">
                <table id="tf_disease_table" class="table table-hover table-bordered table-condensed"
                       cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>TF_name</th>
                        <th>Disease_name</th>
                        <th>Disease_type</th>
                        <th>Disease_class</th>
                        <th width="35%">Disease_semantic_type</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tf_disease_table_sql = "SELECT *
                from $tf_disease
                where tf_disease_gene_symbol='$tf_information_gene_symbol'
                  ";
                    $tf_disease_table_result = mysqli_query($conn, $tf_disease_table_sql);
                    while ($row = mysqli_fetch_assoc($tf_disease_table_result)) {
                        $tf_disease_gene_symbol = $row["tf_disease_gene_symbol"];
                        $tf_disease_disease_name = $row["tf_disease_disease_name"];
                        $tf_disease_disease_type = $row["tf_disease_disease_type"];
                        $tf_disease_disease_class = $row["tf_disease_disease_class"];
                        $tf_disease_disease_semantic_type = $row["tf_disease_disease_semantic_type"];
                        ?>
                        <tr>
                            <td><?php echo $tf_disease_gene_symbol; ?></td>
                            <td><?php echo $tf_disease_disease_name; ?></td>
                            <td><?php echo $tf_disease_disease_type; ?></td>
                            <td><?php echo $tf_disease_disease_class; ?></td>
                            <td><?php echo $tf_disease_disease_semantic_type; ?></td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-folder-info-line"></i> Expression of <font
                            color="red"><?php echo $tf_information_gene_symbol; ?></font>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <!-----<?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_tcga" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>Pan cancer (TCGA)</a>---------->
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_cell_line_encode" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        Cell line (ENCODE)</a>
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_in_vitro_differentiated_cell_encode" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        In vitro differentiated cell (ENCODE)</a>
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_primary_cell_encode" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        Primary cell (ENCODE)</a>
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_tissue_ncbi" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        Tissue (NCBI)</a>
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_normal_tissue_gtex" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        Normal tissue (GTEx)</a>
                        <?php echo '<a href="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_cell_line_ccle" type="button" class="btn btn-primary" target="tf_exp_iframe">'; ?>
                        Cell line (CCLE)</a>
                    </div>
                </div>
                <?php echo '<iframe onload="changeFrameHeight(this);" src="/' . $web_title . '/search/gene_exp_gene_bar.php?tf_name=' . $tf_name . '&gene_exp_type=gene_exp_tcga" name="tf_exp_iframe"
                    scrolling="no"
                    height="100%" width="100%" allowfullscreen="true" allowtransparency="true" frameborder="no"
                    border="0" marginwidth="0" marginheight="0"></iframe>' ?>
            </div>
        </div>
    </div>
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
<?php include(__DIR__ . "/../public/footer.php") ?>

<script>
    Highcharts.chart('gene_distribution', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        credits: {
            enabled: false
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
                end: 100
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
        console.log(option)
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

<!---SE 的染色质分布 start-->
<script>
    var data_regional = <?php echo $start_json;?>;
    //console.log(data_regional);
    var dom = document.getElementById("gene_regional_location");
    var myChart_gene_regional_location = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            },
            formatter: function (obj) {
                var param = obj[0];
                return 'Biosample name:' + ' ' + data_regional.sample_biosample_id[param.dataIndex] + '</br>'
                    + 'Tissue type:' + ' ' + data_regional.sample_tissue_type[param.dataIndex] + '</br>'
                    + 'Genome location:' + ' ' + data_regional.tf2se_se_chr[param.dataIndex] + ':' + data_regional.tf2se_se_start[param.dataIndex] + '-' + data_regional.tf2se_se_end[param.dataIndex] + '</br>'
                    + 'SE score:' + ' ' + data_regional.tf2se_se_score[param.dataIndex] + '</br>';
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottomy: '0%',
            containLabel: true
        },
        dataZoom: [

            {
                show: true,
                yAxisIndex: 0,
                filterMode: 'empty',
                width: 30,
                height: '80%',
                showDataShadow: false,
                left: '98%'
            }
        ],
        xAxis: {
            type: 'value',
            name: 'Genomic regions',
            nameLocation: 'center',
            nameGap: 80,
            nameTextStyle: {
                color: "red",
                fontWeight: "normal",
                fontSize: "18"
            },
            min:<?php echo $min;?>,
            max:<?php echo $max;?>,
            axisLabel:
                {
                    show: true,
                    interval: '0',
                    rotate: 45
                }

        },
        yAxis: {
            type: 'category',
            name: 'Biosample name',
            nameTextStyle: {
                color: "red",
                fontWeight: "normal",
                fontSize: "18"
            },
            splitLine: {show: false},
            data: function () {
                var list = [];
                for (i in data_regional.sample_biosample_id) {
                    list.push(data_regional.sample_biosample_id[i]);
                }
                return list;
            }()
        },
        series: [
            {
                name: '<?php echo $tf_name;?>',
                type: 'bar',
                stack: 'all',
                itemStyle: {
                    normal: {
                        barBorderColor: 'rgba(0,0,0,0)',
                        color: 'rgba(0,0,0,0)'
                    },
                    emphasis: {
                        barBorderColor: 'rgba(0,0,0,0)',
                        color: 'rgba(0,0,0,0)'
                    }
                },
                data: data_regional.tf2se_se_start//start
            },
            {
                name: '<?php echo $tf_name;?>',
                type: 'bar',
                stack: 'all',
                data: data_regional.len//length
            }
        ]
    };
    if (typeof(option.series[0].data) != "undefined") {
        myChart_gene_regional_location.setOption(option, true);
        console.log(option)
    } else {
        myChart_gene_regional_location.showLoading({
                text: 'No data at present',
                color: '#ffffff',
                textColor: '#8a8e91',
                maskColor: 'rgba(255, 255, 255, 0.8)',
                fontSize: 60
            }
        );
    }
</script>
<!---SE 的染色质分布 end-->


<script>
    $(document).ready(function () {
        $('#first_crc').dataTable({
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
                    $(e).attr('title', e.innerHTML);
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#tf_num').dataTable({
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
                    $(e).attr('title', e.innerHTML);
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#gene_distribution_table').dataTable({
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
                    $(e).attr('title', e.innerHTML);
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
                    if (e.innerHTML === '')
                        $(e).html('\\');
                    $(e).attr('title', e.innerHTML);
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#data_coltron_tf2se_table').dataTable({
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
                    $(e).attr('title', e.innerHTML);
                });
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tf_disease_table').dataTable({
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
                    $(e).attr('title', e.innerHTML);
                });
            }
        });
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
</body>
</html>