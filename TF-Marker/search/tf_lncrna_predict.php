<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>LncRNA</title>
    <?php include "../public/link.php"?>
</head>

<body>

<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
include '../public/conn_php.php';
$tf_name = $_GET["tf_name"];
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div style="font-size: 120%; color: red" align="center">LncRNA regulated by <?php echo $tf_name; ?></div>
            <table id="tf_lncrna_predict_table" class="table table-hover table-bordered table-condensed" cellspacing="0"
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
                where tf_lncrna_gene_name='$tf_name'
                  ";
                $tf_lncrna_predict_result = mysqli_query($conn, $tf_lncrna_predict_sql);
                while ($row = mysqli_fetch_assoc($tf_lncrna_predict_result)) {
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
    <!----查询结果 终止-->

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#tf_lncrna_predict_table').dataTable({
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

</body>
</html>
