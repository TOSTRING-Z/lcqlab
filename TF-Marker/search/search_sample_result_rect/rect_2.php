<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php include("../../public/public.php") ?>
    <?php include("../../public/link.php") ?>
    <?php include '../../public/conn_php.php'; ?>
</head>

<body>
<div id="container" style="height:600px;width:100%;display: flex;justify-content: center;align-items:center;"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
<?php
$sample_tf_name = $_GET['sample_tf_name'];
$tf_expression_sql = "SELECT *
        from $Cancer_CellLines_CCLE_gene_Exp_final_ok
        where gene='$sample_tf_name'";
$tf_expression_res = mysqli_query($conn, $tf_expression_sql);
$row = mysqli_fetch_assoc($tf_expression_res);
foreach ($row as $key => $value) {
    /*if ($key == "gene") {
        continue;
    }*/
    $name = $name ? $name . ",'" . $key . "'" : "'" . $key . "'";
    $data = $data ? $data . ",'" . $value . "'" : "'" . $value . "'";
}
if (empty($data)) $data = ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
$data = "[" . $data . "]";
//$name = "[".$name."]";
$name = "['prostate', 'stomach', 'urinary_tract', 'glioma', 'ovary', 'leukemia_other', 'kidney', 'thyroid', 'melanoma', 'soft_tissue', 'upper_aerodigestive', 'lymphoma_DLBCL', 'lung_NSC', 'Ewings_sarcoma', 'mesothelioma', 'T-cell_ALL', 'AML', 'multiple_myeloma', 'endometrium', 'pancreas', 'breast', 'B-cell_lymphoma_other', 'B-cell_ALL', 'lymphoma_Burkitt', 'CML', 'colorectal', 'chondrosarcoma', 'meningioma', 'neuroblastoma', 'lung_small_cell', 'esophagus', 'medulloblastoma', 'T-cell_lymphoma_other', 'fibroblast_like', 'osteosarcoma', 'lymphoma_Hodgkin', 'cervix', 'liver', 'giant_cell_tumour', 'bile_duct', 'other']";
?>
<script type="text/javascript">

    var dom = echarts.init(document.getElementById('container'));
    option = null;
    option = {
        title: {
            text: '<?php echo $sample_tf_name?> Expression in Cancer Cell Lines (CCLE)',
            x: 'center'
        },
        tooltip: {
            trigger: 'axis'
        },
        grid: {x: '5%', y: '10%', width: '90%', height: '75%'},
        toolbox: {
            show: true,
            feature: {
                dataView: {
                    show: true,
                    readOnly: false,
                    title: 'Data view',
                    lang: ['dataView', 'close', 'refresh']
                },
                magicType: {show: true, type: ['line', 'bar'], title: {line: 'Line', bar: 'Bar'}},
                restore: {show: true, title: 'Reset'}
            }
        },
        calculable: true,
        xAxis: [
            {
                name: 'cancer type',
                nameLocation: 'center',
                nameGap: 40,
                type: 'category',
                data: <?php echo $name; ?>,
                axisTick:
                    {
                        show: true,
                        alignWithLabel: 'ture',
                        interval: '0',
                    },
                axisLabel:
                    {
                        show: true,
                        interval: '0',
                        rotate: 30
                    }

            }
        ],
        yAxis: [
            {
                name: 'FPKM',
                type: 'value'
            }
        ],
        series: [{
            itemStyle: itemStyle,
            name: 'Number',
            type: 'bar',
            data:<?php echo $data; ?>
        }]
    };

    if (option && typeof option === "object") {
        dom.setOption(option, true);
    }

</script>
</body>
</html>