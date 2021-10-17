<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>MicroRNA</title>
    <?php include "../public/link.php" ?>
</head>

<body>

<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
include '../../sqlconfig/crcdb/conn_php.php';
$tf_name = $_GET["tf_name"];
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div style="font-size: 120%; color: red" align="center">MicroRNA regulated by <?php echo $tf_name; ?></div>
            <table id="tf2microrna_table" class="table table-hover table-bordered table-condensed" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>TF_name</th>
                    <th>MicroRNA_name</th>
                    <th>MicroRNA_tss</th>
                    <th>MicroRNA_binding_sites</th>
                    <th>MicroRNA_srx</th>
                    <th>Sample_type</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tf_micrna_sql = "SELECT *
                from tf_micrna
                where tf_micrna_tf_name='$tf_name'
                  ";
                $tf_micrna_result = mysql_query($tf_micrna_sql, $conn);
                while ($row = mysql_fetch_assoc($tf_micrna_result)) {
                    $tf_micrna_micrna_name = $row["tf_micrna_micrna_name"];
                    $tf_micrna_tss = $row["tf_micrna_tss"];
                    $tf_micrna_binding_sites = $row["tf_micrna_binding_sites"];
                    $tf_micrna_srx = $row["tf_micrna_srx"];
                    $tf_micrna_sample_type = $row["tf_micrna_sample_type"];
                    ?>
                    <tr>
                        <td><?php echo $tf_name; ?></td>
                        <td><?php echo $tf_micrna_micrna_name; ?></td>
                        <td><?php echo $tf_micrna_tss; ?></td>
                        <td><?php echo $tf_micrna_binding_sites; ?></td>
                        <td><?php echo $tf_micrna_srx; ?></td>
                        <td><?php echo $tf_micrna_sample_type; ?></td>
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
        $('#tf2microrna_table').dataTable({
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
