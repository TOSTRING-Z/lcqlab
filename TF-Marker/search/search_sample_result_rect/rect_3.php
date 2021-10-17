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
        from $ENCODE_Cellline_Gene_EXP_FPKM_ok
        where gene='$sample_tf_name'
          ";
$tf_expression_res = mysqli_query($conn, $tf_expression_sql);
$row = mysqli_fetch_assoc($tf_expression_res);
foreach ($row as $key => $value) {
    /*if ($key == "gene") {
        continue;
    }*/
    $name = $name ? $name . ",'" . $key . "'" : "'" . $key . "'";
    $data = $data ? $data . ",'" . $value . "'" : "'" . $value . "'";
}
//$name = "[".$name."]";
if (empty($data)) $data = ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
$data = "[" . $data . "]";
$name = "['A172', 'A375', 'A549', 'AG04450', 'BE2C', 'BJ', 'Caki2', 'Daoy', 'G401', 'GM12878', 'GM23248', 'GM23338', 'H1-hESC', 'H4', 'H7-hESC', 'HeLa-S3', 'HepG2', 'HT1080', 'HT-29', 'HUES64', 'IMR-90', 'Ishikawa', 'Jurkat clone E61', 'K562', 'Karpas-422', 'LHCN-M2', 'M059J', 'MCF-7', 'MG63', 'NCI-H460', 'OCI-LY7', 'Panc1', 'PC-3', 'PFSK-1', 'RPMI-7951', 'SJCRH30', 'SJSA1', 'SK-MEL-5', 'SK-N-DZ', 'SK-N-SH', 'T47D', 'U-87 MG', 'UCSF-4']";
?>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts图表
    var dom = echarts.init(document.getElementById('container'));
    option = null;
    option = {
        title: {
            text: '<?php echo $sample_tf_name?> Expression in Encode Cell Lines',
            x: 'center'
        },
        tooltip: {
            trigger: 'axis'
        },
        grid: {x: '5%', y: '10%', width: '90%', height: '78%'},
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
