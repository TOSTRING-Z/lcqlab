<?php include "public/public.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body>
<?php include ("public/header.php") ?>
<style>
    *[class*="list-group"] {
        max-width: 280px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .label {
        cursor: pointer;
        padding: 0 5px 0 0;
        line-height: 18px;
        height: 20px;
        display: inline-block;
    }
    .label > div {
        background: #fff;
        display: inline-block;
        height: 100%;
        border: 1px solid #337ab7;
        color: #999;
        cursor: pointer;
        line-height: 18px;
        width: 20px;
        left: 0;
        position: relative;
    }
    .label:hover div {
        color: black;
    }
    .user_select {
        display: inline-block;
        margin: 0 2px 0 0;
        border: 1px solid #f0f0f0;
        padding: 0 5px;
        background: #ff7f00;
        color: white;
        position: relative;
        top: 2px;
        font-weight: bold;
    }
    .list-group-item:last-child {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .list-group-item:first-child {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    table {
        width: 100%;
    }
    .badge {
        background-color: #666;
        border-radius: 3px;
    }

    .list-group-item i {
        float: right;
    }

    th > .list-group-item {
        background: #666;
        color: #fff;
        height: 51px;
    }

    th > .list-group-item > h4 {
        font-weight: bold;
        line-height: 0.6;
    }

    th > .list-group-item {
        background: #666;
    }

    th > .list-group-item i {
        position: relative;
        top: -4px;
    }
    .sm_container {
        z-index: 2;
    }
    .ri-question-line {
        position: relative;
        top: 4px;
    }
    td a{
        white-space: nowrap;
    }
    .info-more {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        max-width: 100px;
        position: relative;
        padding-right: 31px;
    }
    .info-more > i {
        position: absolute;
        right: 2px;
        top: -4px;
        font-size: 22px;
    }
    @media (max-width: 768px) {
        [class*="list-group"] {
            max-width: 100%;
        }
    }
</style>
<div class="container" id="body">
    <div class="row">
        <div class="col-lg-12">
            <br>
        </div>
    </div>

    <div class="row">
        <!--get筛选开始-->
        <div class="col-lg-3 col-md-3 col-xs-12">
            <?php
            ini_set("error_reporting", "E_ALL & ~E_NOTICE");
            include 'public/conn_php.php';
            //处理筛选字符
            $select = !empty($_GET['select']) ? "where {$_GET['select']}" : "";
            //echo $select;
            if ($select != "") {
                $select = preg_replace("/(:'.*?[\w\s])(')([\w].*?'(?:\||))/i", '${1}\'\'$3', $select);
                $select = str_replace(":", "=", $select);
                $select = str_replace("|", " and ", $select);
            }
            //print_r($select);
            //将数据库每列分别读入一个数组$keys中
            foreach (["TissueType", "CellType","GeneType"] as $value){
                $sample_sql = "SELECT $value,count($value) as count from $main $select group by $value;";
                //echo $sample_sql;
                $sample_result = mysqli_query($conn, $sample_sql);
                while ($rows = mysqli_fetch_assoc($sample_result)) {
                    if ($rows[$value] == NULL) continue;
                    $keysRepeat[$value][$rows[$value]] = $rows['count'];
                }
            }
            //print_r($keysRepeat);
            /* 记录数组$keys中重复元素$keysRepeat[name,value]
            foreach ($keys as $key => $value) {
                $keysRepeat[$key] = array_count_values($value);
            }*/
            ?>
            <?php
            //循环输出
            $title = ["Tissue Type", "Cell Type","Gene Type"];
            $j = 0;
            foreach ($keysRepeat as $type => $values) {
                $i = 0;
                if ($type == "TissueType") ksort($values);
                else arsort($values);
                foreach ($values as $name => $val) {
                    $name = trim($name);
                    $val = trim($val);
                    if (preg_match("/[^0-9A-Za-z\.;+\-\(\)\s_']/", $name)) continue;
                    $name_id = preg_replace('/[^0-9A-Za-z]/', "_", $name);
                    $data_select[$type][$i]["id"] = $i;
                    $data_select[$type][$i]["name"] = "<a onclick='select_data(this)' id='$name_id' data-name=\"$name\" data-type='$type'>$name</a>";
                    $data[$type][][0] = "<a title='$name' class='list-group-item' id='$name_id' data-name=\"$name\" data-type='$type'><span class='badge'>$val</span>$name</a>";
                    $i++;
                }
                $j++;
            }
            //print_r($keysRepeat["GeneType"]);
            ?>

            <?php
            $i = 0;
            foreach ($data as $key => $value) {
                $type = $key;
                $key = preg_replace('/[\s,\/]/', "_", $key) . "_";
                echo "<table class='fenye' id=\"$key\">
                            <thead>
                            <tr>
                                <th>
                                    <div class='list-group-item'>
                                        <h4>" . $title[$i] . "<i class='ri-menu-add-line' id='select_$key'></i></h4>
                                        <script>
                                            $('#select_$key').click(function(){
                                            $(this).selectMenu({
                                                showField : 'name',//指定显示文本的字段
                                                keyField : 'id',//指定id的字段
                                                data : " . json_encode($data_select[$type]) . "
                                                });
                                            });
                                        </script>
                                    </div>
                                </th>
                            </tr> 
                            </thead>
                            <tbody></tbody>
                        </table>
                        <ul class=\"pagination\" id=\"" . $key . "_ul\"></ul>";
                $i++;

                echo "<script type='text/javascript'>";
                echo "var data_$key = " . json_encode($value) . ";";
                if ($type == "GeneType") {
                    echo "var $key = new table('$key',data_$key,10);";
                } else
                    echo "var $key = new table('$key',data_$key,5);";
                echo $key . ";";
                echo "</script>";
            } ?>
        </div>

        <div class="col-lg-9 col-md-9 col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator">Browse</b></div>
                </div>
            </div>
            <?php
            $matches = preg_split("/[|]/",$_GET['select']);
            foreach ($matches as $value){
                $sel = preg_split("/[:]/",$value);
                $name = substr($sel[1],1,-1);
                $name_id = preg_replace('/[^0-9A-Za-z]/', "_", $name);
                if (empty($name_id)) continue;
                $type = $sel[0];
                if(!isset($sel1)){
                    $sel1 = "<div class='user_select'>SELECT</div><div onclick='select_data(this)' data-name='$name' data-type='$type' class=\"label label-primary\"><div>X</div> $name</div>";
                    echo $sel1;
                }
                else{
                    echo "+<div onclick='select_data(this)' data-name='$name' data-type='$type' class=\"label label-primary\"><div>X</div> $name</div>";
                }
            };
            if($sel){
                echo "<hr>";
            }
            ?>
            <!--表格开始-->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 id="SNP_Overview">
                        <i class="ri-folder-info-line"></i>  Result of TFs and related markers
                    </h3>
                </div>
                <div class="panel-body">
                    <table id="table_all" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Gene Name</th>
                            <th>Gene Type</th>
                            <th>Interacting Gene</th>
                            <th>Cell Name</th>
                            <th>Cell Type</th>
                            <th>Tissue Type</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--表格结束-->
            <!--cell markers start-->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 id="SNP_Overview">
                        <i class="ri-folder-info-line"></i>  Result of cell types
                    </h3>
                </div>
                <div class="panel-body">
                    <table id="table_cell_name" width="100%">
                        <thead>
                        <tr>
                            <th>Cell Name</th>
                            <th>Gene</th>
                            <th>Supported Sources</th>
                            <th>Word Cloud</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        mysqli_query($conn,"SET SESSION group_concat_max_len=102400;");
                        $sample_table_sql="SELECT 
                            CellName,
                            count(DISTINCT PMID) Supported_Sources,
                            group_concat(DISTINCT GeneName SEPARATOR ', ') GeneName
                            from $main $select group by CellName";
                        $sample_table_result=mysqli_query($conn,$sample_table_sql);
                        while($rows = mysqli_fetch_assoc($sample_table_result)) {
                            if (empty(trim($rows["CellName"]))) continue;
                            echo "<tr>";
                            echo "<td>{$rows["CellName"]}</td>";
                            echo "<td><div class='info-more'>{$rows["GeneName"]}<i onclick='infoMore(this)' class=\"ri-add-circle-line\"></i></div></td>";
                            echo "<td>{$rows["Supported_Sources"]}</td>";
                            echo "<td><a onclick='wordcloud(`{$rows["CellName"]}`,`graph/detail_cell_markers.php`,{CellName:`".urlencode($rows["CellName"])."`,select:`".urlencode($select)."`})'><svg t=\"1613032874567\" class=\"icon\" viewBox=\"0 0 1024 1024\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" p-id=\"557\" width=\"20\" height=\"20\"><path d=\"M881.371429 610.377143H785.066667v295.033905h101.814857c36.59581 2.779429 72.679619-10.410667 100.205714-36.571429 25.35619-30.841905 37.64419-71.68 33.913905-112.713143 2.82819-40.350476-11.142095-79.969524-38.107429-108.129524-26.965333-28.16-64.146286-41.935238-101.473524-37.619809z m-29.013334 66.072381h26.819048c48.201143 0 70.631619 26.575238 71.728762 80.847238 1.097143 54.296381-21.894095 84.382476-71.168 84.382476h-27.355429v-165.229714z m-437.930666-266.727619L191.073524 1019.928381h120.441905l54.735238-154.624h218.965333l54.759619 154.624h122.075429L533.211429 409.746286h-118.784z m59.123809 138.678857v11.215238s8.777143 23.015619 72.801524 201.825524h-147.260952c59.684571-167.594667 71.192381-198.875429 73.362285-203.580953l1.097143-9.459809zM199.826286 619.812571c-109.470476-6.485333-168.594286-74.361905-174.616381-201.825523 4.388571-133.948952 63.488-200.630857 174.08-208.286477 71.119238-2.608762 135.070476 46.470095 156.013714 119.783619l5.461333 17.115429-82.115047 23.015619-4.388572-15.36c-7.558095-37.64419-38.692571-64.316952-74.435047-63.707428-34.474667 0-83.21219 18.870857-88.673524 126.268952 3.82781 106.22781 54.735238 122.733714 89.234286 126.293333 43.008 0.975238 78.726095-35.669333 79.920762-82.041905l3.291428-17.700571 81.017905 25.356191-3.291429 14.774857c-11.337143 85.26019-83.090286 145.700571-162.596571 136.899047l1.097143-0.585143z m783.384381-240.152381c-2.876952-52.49219-34.54781-98.011429-80.457143-115.687619 37.571048-22.162286 60.269714-65.365333 58.563047-111.518476-4.388571-56.07619-36.132571-123.928381-164.230095-128.048762H597.820952v505.124572h214.064762c141.775238 0 171.324952-81.432381 171.324953-149.894095z m-103.472762-4.729904c0 31.865905-8.752762 69.022476-78.823619 69.022476h-106.203429v-128.633905h101.814857c71.728762 1.77981 82.115048 35.401143 83.772953 59.611429h-0.560762z m-91.428572-149.894096h-93.598476V112.90819h93.622857c65.145905 0 73.874286 30.110476 74.435048 51.931429 0.560762 21.845333-9.849905 60.196571-74.435048 60.196571z\" fill=\"#1296db\" p-id=\"558\" style=\"user-select: auto;\"></path></svg></a></td>";
                            echo "<td><a target=\"_blank\" onclick='doPost(`search/detail_cell.php`, {CellName:`".urlencode($rows["CellName"])."`,select:`".urlencode($select)."`})'>more details</a></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--cell markers end-->
        </div>


    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div id="container" style="height: 100%;width: 100%"></div>
                <script>
                    window.chart = Highcharts.chart('container', {
                        series: [{
                            type: 'wordcloud',
                            data: []
                        }],
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: null
                        }
                    });
                    function wordcloud(name,url,data) {
                        $.ajax({
                            url: url,
                            data: data,
                            type: "post",
                            dataType: "json"
                        }).then(function (data) {
                            $("#myModalLabel").html(name);
                            $('#myModal').modal('show')
                            window.chart.update({
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
                            });
                        })
                    }
                </script>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<?php include 'public/footer.php'?>
</body>
<script>
    $('#table_cell_name').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>'
        }],
        order: [[2, "desc"]],
        scrollX: true,
        createdRow: function (row, data, dataIndex) {
            $(row).children('td').each((i, e) => {
                switch (i) {
                    case 3:
                        return
                    default:
                        break
                }
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });
    var dTable = $('#table_all').DataTable({
        dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-folder-download-line"></i>',
            action: function (e, dt, node, config) {
                dt.draw().page();
                var this_ = this;
                var formData = dt.ajax.params();
                formData.start =  formData.length;
                formData.length = parseInt(dt.ajax.json().recordsTotal)-1;
                $.ajax({
                    url: 'browse_server.php',
                    method: 'post',
                    dataType:"json",
                    data: formData
                }).then(function (ajaxReturnedData) {
                    dt.rows.add(ajaxReturnedData.data).draw();

                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this_,e, dt, node, config);

                });
            }
        }],
        "scrollX": true,
        "paging": true,
        "pagingType": "full_numbers",
        "lengthMenu": [10, 25, 50, 100],
        "processing": true,
        "searching": true, //是否开启搜索
        "serverSide": true,//开启服务器获取数据
        "order": [[6, "asc"]], //默认排序
        "ajax": { // 获取数据
            "url": "browse_server.php",
            "dataType": "json", //返回来的数据形式
            "method": "post",
            "data": {
                "select": "<?php echo $_GET['select'] ?>"
            }
        },
        "bPaginage": true,
        "columns": [
            {"data": "GeneName"},
            {"data": "GeneType"},
            {"data": "Interacting_Gene_Symbol"},
            {"data": "CellName"},
            {"data": "CellType"},
            {"data": "TissueType"},
            {"data": "id"},
        ],
        "language": { // 定义语言
            "sProcessing": "Loading...",
            "sLengthMenu": "Show_MENU_ entries",
            "sZeroRecords": "No matching results",
            "sInfo": "From _START_ to _END_ results，Total _TOTAL_ items",
            "sInfoEmpty": "Showing results 0 to 0，Total_ 0 _items",
            "sInfoFiltered": "",
            "sInfoPostFix": "",
            "sSearch": "Table Search:",
            "sUrl": "",
            "sEmptyTable": "The data in the table is empty",
            "sLoadingRecords": "Loading...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Previous",
                "sNext": "Next",
                "sLast": "Last"
            },
            "oAria": {
                "sSortAscending": ": Ascending this column in ascending order",
                "sSortDescending": ": Sort this column in descending order"
            }
        },
        "columnDefs": [{
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                var t = row.Interacting_Gene_Symbol?row.Interacting_Gene_Symbol:"";
                t = t.split(';').join(', ');
                var html = "<div class='info-more'>"+ t +"<i onclick='infoMore(this)' class=\"ri-add-circle-line\"></i></div>";
                return html;
            }
        },{
            "targets": -1,
            "data": null,
            "render": function (data, type, row) {
                var html = '<a target="_blank" href="search/detail_all.php?id=' + row.id + '">more details</a>';
                return html;
            }
        }],
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').each((i, e) => {
                if (e.innerText === '')
                    $(e).html('\\');
                $(e).attr('title', e.innerText);
            });
        }
    });
    $('<i style="font-size: 20px" class="ri-question-line" title="TF-Marker display all the entries about the input based on the filter."></i>').insertBefore('#table_all_filter > label')
</script>
<script>
    // $(document).ready(function () {
    //     var e = $('.ri-menu-add-line')[1];
    //     e.className = 'ri-question-line';
    //     e.title = 'We calculated score of the variation that means how many categories the variation associated with. Each variation is scored based on its annotated records on nine annotation categories: risk SNP, eQTL, motif change, conservation, enhancer and super enhancer, promoter, TF binding, ATAC accessible region and Hi-C.';
    // })
</script>
</html>


