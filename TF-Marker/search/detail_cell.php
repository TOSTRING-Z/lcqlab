<?php include ("../public/public.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
    <style>
        #up {
            position: fixed;
            display: block;
            right: 50px;
            bottom: 50px;
            width: 50px;
            height: 50px;
            background-color: black;
            border-radius: 2px;
            opacity: 0.7;
            -moz-box-shadow: 2px 2px 5px #333333;
            -webkit-box-shadow: 2px 2px 5px #333333;
            box-shadow: 2px 2px 5px #333333;
            z-index: 100;
        }
        #up:focus, #up:hover {
            text-decoration: none;
        }
        #up > i {
            position: relative;
            left: 27%;
            top: 16%;
            font-size: x-large;
            font-weight: bold;
            color: #fff;
        }
    </style>
</head>
<body data-spy="scroll" data-target="#myScrollspy">
<?php include ("../public/header.php") ?>
<a id="up" href="#up_up">
    <i class="ri-arrow-up-line"></i>
</a>
<div class="container">
    <div class="row" id="up_up">
    <div class="col-xs-12 col-lg-12">
        <div class="pull-right"><i class="ri-map-pin-line"></i> Search / <b class="navigator">Detail</b></div>
    </div>
    </div>
    <hr>
    <?php
    ini_set("error_reporting", "E_ALL & ~E_NOTICE");
    include '../public/conn_php.php';
    $CellName = urldecode($_POST['CellName']);
    $select =  urldecode($_POST['select']);
    if(!empty($select)) $select_ = "$select and";
    else $select_ = "where";
    $query = mysqli_query($conn, "SELECT * from $main $select_ CellName='$CellName'");
    while ($row = mysqli_fetch_assoc($query)){
        $data[] = $row;
    }
    foreach ($data as $row){
        $PMID[] = $row["PMID"];
        $GeneName[] = $row["GeneName"];
        $CellType[] = $row["CellType"];
        $TissueType[] = $row["TissueType"];
    }
    $Supported_Sources = count(array_unique($PMID));
    $GeneName = join(", ",array_unique($GeneName));
    $CellType = join(", ",array_unique($CellType));
    $TissueType = join(", ",array_unique($TissueType));
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 id="SNP_Overview">
                <i class="ri-folder-info-line"></i> Overview
            </h3>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-color-1" height-to="right">
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <td>CellName</td>
                                <td><?php echo $CellName?></td>
                            </tr>
                            <?php
                            $matches = preg_split("/ and /",$select);
                            $matches = preg_replace("/where /","",$matches);
                            $convert = array(
                                    "CellName" => "Cell Name",
                                    "TissueType" => "Tissue Type",
                                    "CellType" => "Cell Type",
                                    "GeneType" => "Gene Type"
                            );
                            foreach ($matches as $value){
                                $sel = preg_split("/=/",$value);
                                $type = trim($sel[0]);
                                $name = substr($sel[1],1,-1);
                                $name_id = preg_replace('/[^0-9A-Za-z]/', "_", $name);
                                if (empty($name_id)) continue;
                                echo "<tr>
                                        <td>{$convert[$type]}</td>
                                        <td>{$name}</td>
                                    </tr>";
                            }
                            ?>
                            <tr>
                                <td>Supported Sources</td>
                                <td><?php echo count(array_unique($PMID))?></td>
                            </tr>
                            <tr>
                                <td>Gene</td>
                                <td><?php echo $GeneName?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box box-color-2" id="right">
                        <div id="info_draw_bar_" onmouseleave="$('#info_draw_bar_').toggle(0)" style="display: none;
                                                position: absolute;
                                                border: 1px solid #ddd;
                                                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
                                                border-radius: 5px;
                                                background-color: white;
                                                z-index: 2;
                                                margin: 40px 15px 0 15px;
                                                padding: 5px;">

                        <i onclick="$('#info_draw_bar_').toggle(500)" class="ri-questionnaire-fill pull-right"
                           style="z-index: 1"></i>

                        </div>
                        <h4><strong>
                                <center></center>
                            </strong></h4>
                        <div id="container" style="height: 100%;width: 100%;display: flex;justify-content: center;align-items:center;"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                        <script>
                            $.ajax({
                                url: "../graph/detail_cell_markers.php",
                                data: {CellName:"<?php echo urlencode($CellName) ?>",select:"<?php echo urlencode($select) ?>"},
                                type: 'post',
                                dataType: "json"
                            }).then(function (data) {
                                Highcharts.chart('container', {
                                    series: [{
                                        type: 'wordcloud',
                                        data: data,
                                        spiral: 'archimedean',
                                        rotation: {
                                            from: 0,
                                            orientations: 1,
                                            to: 0
                                        }
                                    }],
                                    credits: {
                                        enabled: false
                                    },
                                    title: {
                                        text: null
                                    }
                                });
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <i class="ri-folder-info-line"></i> <b><font color="red"><?php echo $CellName ?></font></b>
            </h3>
        </div>
        <div class="panel-body">
            <table id="Detail">
                <thead>
                <tr>
                    <th>PMID</th>
                    <th>GeneName</th>
                    <th>GeneType</th>
                    <th>CellType</th>
                    <th>TissueType</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($data as $row){
                    echo "<tr>
                            <td><a href='#PMID_{$row["PMID"]}'>{$row["PMID"]}</a></td>
                            <td>{$row["GeneName"]}</td>
                            <td>{$row["GeneType"]}</td>
                            <td>{$row["CellType"]}</td>
                            <td>{$row["TissueType"]}</td>
                            <td><a target='_blank' href='/$web_title/search/detail_all.php?id={$row["id"]}'>more detail</a></td>
                          </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    $key = 0;
    foreach (array_unique($PMID) as $pmid) {
        $key ++;
        $query = mysqli_query($conn, "select *
        from $main $select_ PMID=$pmid
        order by ExperimentType");
        $Function = [];
        $GeneName = [];
        while($row = mysqli_fetch_assoc($query)){
            $Title = $row["Title"];
            $ExperimentType = $row["ExperimentType"];
            $ExperimentalMethod = $row["ExperimentalMethod"];
            $Function[] = $row["Function"];
            $GeneName[] = $row["GeneName"];
        }
        ?>
        <div class="panel panel-default" id="PMID_<?php echo $pmid?>">
            <div class="panel-heading">
                <h3>
                    <i class="ri-open-source-line "></i> Source <?php echo $key ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="box box-color-1">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                        <tr><td>PMID</td><td><a href="https://pubmed.ncbi.nlm.nih.gov/<?php echo $pmid ?>"><?php echo $pmid ?></td></tr>
                        <tr><td>Title</td><td><?php echo $Title ?></td></tr>
                        <tr><td>ExperimentType</td><td><?php echo $ExperimentType ?></td></tr>
                        <tr><td>ExperimentalMethod</td><td><?php echo $ExperimentalMethod ?></td></tr>
                        <tr><td>Description of Gene</td><td><?php echo join("<i style='color:#3298DB;font-size: inherit;' class=\"ri-single-quotes-r\"></i>",$Function) ?></td></tr>
                        <tr><td>Gene</td><td><?php echo join(", ",$GeneName) ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
<?php include '../public/footer.php' ?>
<script>
    $('#Detail').DataTable({
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
                $(e).attr('title', e.innerText);
            });
        }
    });
</script>
</body>
</html>
