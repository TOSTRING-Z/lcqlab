<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>mRNA</title>
    <?php include "../public/link.php" ?>
</head>

<body>

<?php
include '../public/conn_php.php';
$tf_name = $_GET["tf_name"];
?>
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div style="font-size: 120%; color: red" align="center">Gene regulated by <?php echo $tf_name; ?></div>
            <table id="tf_gene_table" class="table table-hover table-bordered table-condensed" cellspacing="0"
                   width="100%">
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
                where tf_gene_tf_name='" . $tf_name . "'
                  ";
                $tf_micrna_result = mysqli_query($conn, $tf_micrna_sql);
                while ($row = mysqli_fetch_assoc($tf_micrna_result)) {
                    $tf_gene_gene_name = $row["tf_gene_gene_name"];
                    $tf_gene_tf_name = $row["tf_gene_tf_name"];
                    $tf_gene_influence_type = $row["tf_gene_influence_type"];
                    $tf_gene_pubmed = $row["tf_gene_pubmed"];
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
        $('#tf_gene_table').dataTable({
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
