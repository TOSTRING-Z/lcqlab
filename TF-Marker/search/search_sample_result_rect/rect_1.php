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
        from $figure_tf_expression
        where gene_name='$sample_tf_name'
          ";
$tf_expression_res = mysqli_query($conn, $tf_expression_sql);
while ($row = mysqli_fetch_assoc($tf_expression_res)) {
    $cancer_ACC = $row["ACC"];
    $cancer_BLCA = $row["BLCA"];
    $cancer_CESC = $row["CESC"];
    $cancer_CHOL = $row["CHOL"];
    $cancer_COAD = $row["COAD"];
    $cancer_DLBC = $row["DLBC"];
    $cancer_ESCA = $row["ESCA"];
    $cancer_GBM = $row["GBM"];
    $cancer_HNSC = $row["HNSC"];
    $cancer_KICH = $row["KICH"];
    $cancer_KIRC = $row["KIRC"];
    $cancer_LGG = $row["LGG"];
    $cancer_LIHC = $row["LIHC"];
    $cancer_LUAD = $row["LUAD"];
    $cancer_LUSC = $row["LUSC"];
    $cancer_MESO = $row["MESO"];
    $cancer_NBL = $row["NBL"];
    $cancer_OV = $row["OV"];
    $cancer_PAAD = $row["PAAD"];
    $cancer_PCPG = $row["PCPG"];
    $cancer_PRAD = $row["PRAD"];
    $cancer_READ = $row["READ"];
    $cancer_SARC = $row["SARC"];
    $cancer_SKCM = $row["SKCM"];
    $cancer_STAD = $row["STAD"];
    $cancer_TGCT = $row["TGCT"];
    $cancer_THCA = $row["THCA"];
    $cancer_THYM = $row["THYM"];
    $cancer_UCEC = $row["UCEC"];
    $cancer_UCS = $row["UCS"];
    $cancer_UVM = $row["UVM"];
    $cancer_WT = $row["WT"];
    $cancer_AML = $row["AML"];
    $cancer_LAML = $row["LAML"];
    $cancer_RT = $row["RT"];
    $cancer_BRCA = $row["BRCA"];
}
?>
<script type="text/javascript">

    // 基于准备好的dom，初始化echarts图表
    var dom = echarts.init(document.getElementById('container'));
    option = null;
    option = {
        title: {
            text: '<?php echo $sample_tf_name?> Expression in Human Cancers (TCGA)',
            x: 'center'
        },
        tooltip: {
            trigger: 'axis'
        },

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
                data: ['ACC', 'BLCA', 'CESC', 'CHOL', 'COAD', 'DLBC', 'ESCA', 'GBM', 'HNSC', 'KICH', 'KIRC', 'LGG', 'LIHC', 'LUAD', 'LUSC', 'MESO', 'NBL', 'OV', 'PAAD', 'PCPG', 'PRAD', 'READ', 'SARC', 'SKCM', 'STAD', 'TGCT', 'THCA', 'THYM', 'UCEC', 'UCS', 'UVM', 'WT', 'AML', 'LAML', 'RT', 'BRCA'],
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
                name: 'count',
                type: 'value'
            }
        ],
        grid: {x: '5%', y: '10%', width: '90%', height: '80%'},
        series: [{
            itemStyle: itemStyle,
            name: 'Number',
            type: 'bar',
            data: [<?php echo $cancer_ACC . "," . $cancer_BLCA . "," . $cancer_CESC . "," . $cancer_CHOL . "," . $cancer_COAD . "," . $cancer_DLBC . "," . $cancer_ESCA . "," . $cancer_GBM . "," . $cancer_HNSC . "," . $cancer_KICH . "," . $cancer_KIRC . "," . $cancer_LGG . "," . $cancer_LIHC . "," . $cancer_LUAD . "," . $cancer_LUSC . "," . $cancer_MESO . "," . $cancer_NBL . "," . $cancer_OV . "," . $cancer_PAAD . "," . $cancer_PCPG . "," . $cancer_PRAD . "," . $cancer_READ . "," . $cancer_SARC . "," . $cancer_SKCM . "," . $cancer_STAD . "," . $cancer_TGCT . "," . $cancer_THCA . "," . $cancer_THYM . "," . $cancer_UCEC . "," . $cancer_UCS . "," . $cancer_UVM . "," . $cancer_WT . "," . $cancer_AML . "," . $cancer_LAML . "," . $cancer_RT . "," . $cancer_BRCA; ?>]
        }]
    };

    if (option && typeof option === "object") {
        dom.setOption(option, true);
    }

</script>
</body>
</html>