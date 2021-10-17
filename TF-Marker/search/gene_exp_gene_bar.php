<!DOCTYPE html>
<html>
<head>
    <?php include '../public/link.php'; ?>
</head>

<body>

<?php
  include '../public/conn_php.php';
  $tf_name=$_GET["tf_name"];
  $gene_exp_type=$_GET["gene_exp_type"];
  switch($gene_exp_type){
    case 'gene_exp_tcga': 
      $title = "gene_exp_tcga"; 
      break;
    case 'gene_exp_cell_line_encode': 
      $title = "Expression of TFs in cell line (ENCODE)"; 
      break;
    case 'gene_exp_in_vitro_differentiated_cell_encode': 
      $title = "Expression of TFs in vitro differentiated cell (ENCODE)"; 
      break;
    case 'gene_exp_primary_cell_encode': 
      $title = "Expression of TFs in primary cell (ENCODE)"; 
      break;
    case 'gene_exp_tissue_ncbi': 
      $title = "Expression of TFs in tissue (NCBI)"; 
      break;
    case 'gene_exp_normal_tissue_gtex': 
      $title = "Expression of TFs in normal tissues (GTEx)"; 
      break;
    case 'gene_exp_cell_line_ccle': 
      $title = "Expression of TFs in cell lines (CCLE)"; 
      break;
  }
  $gene_exp_tissue_sql="SELECT *
        from crcdb.$gene_exp_type
        where gene_name='$tf_name'";
      $gene_exp_tissue_res=mysqli_query($conn,$gene_exp_tissue_sql);
      $row = mysqli_fetch_assoc($gene_exp_tissue_res);
      foreach ($row as $key => $value) {
            if ($key=="gene_name") continue;
            if ($key=="other") continue;
            $name = $name?$name.",'".$key."'":"'".$key."'";
            $data = $data?$data.",'".$value."'":"'".$value."'";
          }
      $name = "[".$name."]";
      $data = "[".$data."]";
?>

<div class="container">
  <div class="row">
    <div class="col-lg-12"> 
      <div id="gene_exp_bar" style="height:500px;width:100%;"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
var dom = document.getElementById("gene_exp_bar");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {
    title : {
        text: '<?php echo $title;?>',
        left: 'center'
    },
    tooltip : {
        trigger: 'axis'
    },
    
    toolbox: {
        show : true,
        feature : {
            dataView : {show: true, readOnly: false, title:'Data view',lang:['dataView','close','refresh']},
            magicType : {show: true, type: ['line', 'bar'], title:{line:'Line',bar:'Bar'}},
            restore : {show: true, title:'Reset'},
            saveAsImage : {show: true, title:'Download'}
        }
    },
    calculable : true,
    xAxis : [
        {
      
            type : 'category',
            data : <?php echo $name; ?>,
                name: 'Sample type',
                nameTextStyle: {
                    color: "red",
                    fontWeight: "normal",
                    fontSize : "18"
                },
      axisTick: 
      {
        show: true,
        alignWithLabel : 'ture',
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
    yAxis : [
        {
            type : 'value',
            name: 'FPKM',
            nameLocation: 'center',
                nameGap: 30,
                nameTextStyle: {
                    color: "red",
                    fontWeight: "normal",
                    fontSize : "18"
                },
        }
    ],
    grid: {
      right: '20%',
      bottom:'30%'
    },
    series : [
        {           
            name:'Number',
            type:'bar',
            data:<?php echo $data; ?>,
            itemStyle: {
                normal: {
                    //每根柱子颜色设置
                    color: function(params) {
                        let colorList = [
                            '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                            '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                            '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0',
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#749f83","#ca8622","#bda29a","#6e7074","#546570",
                            "#c4ccd3","#4BABDE", "#FFDE76","#E43C59","#37A2DA",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#749f83","#ca8622","#bda29a","#6e7074","#546570",
                            "#c4ccd3","#4BABDE", "#FFDE76","#E43C59","#37A2DA",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae",
                            "#749f83","#ca8622","#bda29a","#6e7074","#546570",
                            "#c4ccd3","#4BABDE", "#FFDE76","#E43C59","#37A2DA",
                            "#c23531","#2f4554","#61a0a8","#d48265","#91c7ae"
                        ];
                        return colorList[params.dataIndex];
                    }
                }
            }
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
</script>
</body>
</html>