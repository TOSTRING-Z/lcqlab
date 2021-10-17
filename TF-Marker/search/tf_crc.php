<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css" />
    <link rel="stylesheet" href="../public/css/footer.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.staticfile.org/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
</head>

<body>

<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include '../../sqlconfig/crcdb/conn_php.php';
$tf_name=$_GET["tf_name"];
$sample_id=$_GET["sample_id"];
$strategy=$_GET["strategy"];
?>
<div class="container-fluid">
    <center><h4 style="color:red" id="title"></h4></center>
    <div class="row">
        <div class="col-lg-12">
            <table id="data_coltron_all_table" class="table table-hover table-bordered" cellspacing="0" width="100%" >
                <thead>
                <tr>
                    <th>CRC rank</th>
                    <th>TF list of CRCs</th>
                    <th>CRC score</th>
                    <th>TF number of CRC</th>
                    <th>Deatil of CRC</th>
                </tr>
                </thead>
                <tbody>
                <?php
                ini_set("error_reporting","E_ALL & ~E_NOTICE");
                include '../../sqlconfig/crcdb/conn_php.php';
                $tf_crc_sql="SELECT * FROM
                (SELECT
                tem.sample_biosample_id,
                data_".$strategy."_all.crc_sample_id,
                data_".$strategy."_all.crc_id,
                data_".$strategy."_all.crc_list,
                data_".$strategy."_all.crc_score,
                data_".$strategy."_all.crc_num,
                data_".$strategy."_all.crc_rank
                FROM
                (SELECT sample_biosample_id,sample_id
                FROM sample_id
                WHERE sample_id = '$sample_id')
                AS tem
                JOIN data_".$strategy."_all
                ON data_".$strategy."_all.crc_sample_id = tem.sample_id)
                AS tem1
                WHERE FIND_IN_SET('$tf_name',tem1.crc_list )";

                $tf_crc_result=mysql_query($tf_crc_sql,$conn);
                while($row = mysql_fetch_assoc($tf_crc_result)){
                    $sample_biosample_id=$row["sample_biosample_id"];
                    $crc_sample_id=$row["crc_sample_id"];
                    $crc_id=$row["crc_id"];
                    $crc_list=$row["crc_list"];
                    $crc_score=$row["crc_score"];
                    $crc_num=$row["crc_num"];
                    $crc_rank=$row["crc_rank"];
                    ?>
                    <tr>
                        <td><?php echo $crc_rank;?></td>
                        <td><i class="fa fa-plus-circle" style="font-size:15px;"></i> <?php echo $crc_list;?></td>
                        <td><?php echo $crc_score;?></td>
                        <td><?php echo $crc_num;?></td>
                        <td><a href='crc_detail.php?strategy=<?php echo $strategy;?>&sample_id=<?php echo $crc_sample_id;?>&crc_id=<?php echo $crc_id;?>' target='_blank'>detail</a></td>
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

    $(document).ready(function() {
        $('#data_coltron_all_table').dataTable( {
            "dom": '<"top"i>rt<"bottom"flp><"clear">'
        } );
    } );

    document.getElementById("title").innerText = "<?php echo  $sample_biosample_id; ?>";

    var plus = new Array;
    var vals = new Array;
    $("#data_coltron_all_table .fa-plus-circle").each(function(index,doc){
        vals = doc.nextSibling.nodeValue.split(",");
        var val = "";
        for(i in vals){
            if (i<4)
                val = val==""?vals[0]:val+","+vals[i];
            else if (i==vals.length-5)
                val = val+"......";
            else if(i>vals.length-5)
                val =  i==vals.length-4?val+vals[i]:val+","+vals[i];
        }
        plus.push({"val":val,"vals":vals});
        doc.nextSibling.nodeValue = val;
    })
    /*默认展示前3行
    $(document).ready(function(){
      $("#crc_all_table .fa-plus-circle").each(function(index,doc){
       if(index<3){
              var text = "";
              for(i in plus[index].vals){
                text = text==""?plus[index].vals[i]:text+","+plus[index].vals[i];
                if(i%20==0&&i!=0)
                text = text+"<br>";
              }
              doc.classList.toggle("fa-plus-circle",false);
              doc.classList.toggle("fa-minus-circle",true);
              var html = "<tr><td colspan='4'>"+text+"</td></tr>";
              $(doc).parent().parent().after(html);
            }
        })
    })
    */
    $("#data_coltron_all_table .fa-plus-circle").parent().css("width","50%");
    $("#data_coltron_all_table td").click(function(e){
        var doc = e.target;
        if (doc.classList[1] == "fa-plus-circle"){
            doc.classList.toggle("fa-plus-circle",false);
            doc.classList.toggle("fa-minus-circle",true);
            var text_index = doc.parentNode._DT_CellIndex.row;
            var text = "";
            for(i in plus[text_index].vals){
                text = text==""?plus[text_index].vals[i]:text+","+plus[text_index].vals[i];
                if(i%20==0&&i!=0)
                    text = text+"<br>";
            }
            var html = "<tr><td colspan='5'>"+text+"</td></tr>";
            $(doc).parent().parent().after(html);
        }
        else if (doc.classList[1] == "fa-minus-circle"){
            doc.classList.toggle("fa-plus-circle",true);
            doc.classList.toggle("fa-minus-circle",false);
            $(doc).parent().parent().next().remove();
        }
    });
    $("#data_coltron_all_table").parent().click(function(e){
        if (e.target.parentElement.parentElement.className ==  "pagination") {
            $(".fa-minus-circle").each(function(index,doc){
                doc.classList.toggle("fa-plus-circle",true);
                doc.classList.toggle("fa-minus-circle",false);
            });
        }
    });
</script>

</body>
</html>
