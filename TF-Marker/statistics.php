<?php include(__DIR__ . "/public/public.php");
ini_set("error_reporting", "E_ALL & ~E_NOTICE"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body>
<?php include(__DIR__ . "/public/header.php") ?>
<style>
    input.form-control {
        width: 100%;
    }

    .form-group {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
    }

    .form-group > div {
        width: -moz-available;
    }
    label {
        padding-top: 5px;
        white-space: nowrap;
    }
</style>
<?php
include "public/conn_php.php";
$info_sql = "select 
    TissueType,count(id) as number from $main
    where TissueType is not null
    group by TissueType
    order by number";
$query = mysqli_query($conn, $info_sql);
$TissueType_data = mysqli_fetch_all($query);

$info_sql = "select 
    CellName,count(id) as number from $main
    where CellName is not null
    group by CellName
    order by number";
$query = mysqli_query($conn, $info_sql);
$CellName_data = mysqli_fetch_all($query);
?>
<div class="container" id="body">
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator">Statistics</b></div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h2><b><i class="ri-information-line"></i> Tissue information</b></h2>
            <hr>
        </div>

        <div class="col-lg-12">
            <div class="box box-color-3">
                <div class="row">
                    <div class="col-lg-6">
                        <div style="width: 100%;height: 400px;display: flex;justify-content: center;align-items:center;" id="index_tissue_pie"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <div style="width: 100%;height: 400px;display: flex;justify-content: center;align-items:center;" id="index_tissue_count"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <hr>
            <table id="tissue_table" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>Tissue Type</th>
                    <th>Count</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h2><b><i class="ri-information-line"></i> Cell information</b></h2>
            <hr>
        </div>

        <div class="col-lg-12">
            <div class="box box-color-3">
                <div class="row">
                    <div class="col-lg-6">
                        <div style="width: 100%;height: 400px;display: flex;justify-content: center;align-items:center;" id="index_cell_pie"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <div style="width: 100%;height: 400px;display: flex;justify-content: center;align-items:center;" id="index_cell_count"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <hr>
            <table id="cell_table" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>Cell Name</th>
                    <th>Count</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h2><b><i class="ri-information-line"></i> Distribution of genes in tissue and cell</b></h2>
            <hr>
        </div>
        <div class="col-lg-12">
            <div class="box box-color-1">
                <form action="search/search_by_Marker.php" method="get" target="_blank" id="form_search_by_Marker">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>Tissue Type:&nbsp;</label>
                                <div id="tissue_type"></div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>Cell Name:&nbsp;</label>
                                <div id="cell_name"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 pull-right">
                            <button type="button" onclick="search_detail()" class="btn btn-primary">Submit</button>
                        </div>

                    </div>
                </form>
                <script>
                    window.tis_type = new reinput({
                        name: "tis_type",
                        target: "#tissue_type",
                        ajax: {
                            url: "/<?php echo $web_title?>/search/search_cell_tissue_server.php?input_sel=TissueType",
                            //data: {'sel': 'gwas_catalog_2019_hg19_ucsc'}
                        },
                        api: {
                            change: function () {
                                window.tis_type.change(search_tissue_cell)
                            }
                        }
                    });
                </script>
                <script>
                    window.cel_name = new reinput({
                        name: "cel_name",
                        target: "#cell_name",
                        ajax: {
                            url: "/<?php echo $web_title?>/search/search_cell_tissue_server.php?input_sel=CellName",
                        },
                        api: {
                            change: function () {
                                window.cel_type.change(search_tissue_cell)
                            }
                        }
                    });
                    var search_tissue_cell = [window.cel_name,window.tis_type]
                </script>
            </div>
            <div id="all_marker_div">
            </div>
        </div>
        <div class="col-lg-12" id="marker_table_div">

        </div>
    </div>
    <hr>
</div>
<?php include "public/footer.php"; ?>
</body>
<script>
    var tissue_data = <?php echo json_encode($TissueType_data)?>;
    $(document).ready(function () {
        $('#tissue_table').DataTable({
            dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="ri-folder-download-line"></i>'
            }],
            order: [[1, "desc"]],
            data: tissue_data,
            columns: [
                {title: "Tissue Type"},
                {title: "Count"}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).children('td').each(function (i, e) {
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
    });
    var dom = document.getElementById("index_tissue_pie");
    var myCharts = echarts.init(dom);
    var app = {};
    option = null;

    option = {
        tooltip: {
            trigger: 'item'
        },
        legend: {
            type: 'scroll',
            top: 'bottom'
        },
        series: [
            {
                name: 'Tissue type',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '40',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: tissue_data.map(function (row) {
                    return {
                        name: row[0],
                        value: row[1],
                    }
                }),
                itemStyle: itemStyle
            }
        ]
    };
    if (option && typeof option === "object") {
        myCharts.setOption(option, true);
    }

    var dom = document.getElementById("index_tissue_count");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        xAxis: {
            type: 'category',
            name: 'Tissue',
            data: tissue_data.map(function (row) {
                return row[0]
            })
        },
        yAxis: {
            type: 'value',
            name: 'number'
        },

        dataZoom: [{
            type: 'inside',
            start: 80,
            end: 100
        }, {
            start: 0,
            end: 100
        }],
        series: [{
            data: tissue_data.map(function (row) {
                return row[1]
            }),
            label: {
                show: true,
                position: 'top'
            },
            type: 'bar',
            showBackground: true,
            backgroundStyle: {
                color: 'rgba(180, 180, 180, 0.2)'
            },
            itemStyle: itemStyle
        }]
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>
<script>
    var cell_data = <?php echo json_encode($CellName_data)?>;
    $(document).ready(function () {
        $('#cell_table').DataTable({
            dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="ri-folder-download-line"></i>'
            }],
            order: [[1, "desc"]],
            data: cell_data,
            columns: [
                {title: "Cell Name"},
                {title: "Count"}
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).children('td').each(function (i, e) {
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
    });
    var dom = document.getElementById("index_cell_pie");
    var myCharts = echarts.init(dom);
    var app = {};
    option = null;

    option = {
        tooltip: {
            trigger: 'item'
        },
        legend: {
            type: 'scroll',
            top: 'bottom'
        },
        series: [
            {
                name: 'Cell type',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '40',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: cell_data.map(function (row) {
                    return {
                        name: row[0],
                        value: row[1],
                    }
                }),
                itemStyle: itemStyle
            }
        ]
    };
    if (option && typeof option === "object") {
        myCharts.setOption(option, true);
    }

    var dom = document.getElementById("index_cell_count");
    var myChart = echarts.init(dom);
    var app = {};
    option = null;
    option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        xAxis: {
            type: 'category',
            name: 'Cell',
            data: cell_data.map(function (row) {
                return row[0]
            })
        },
        yAxis: {
            type: 'value',
            name: 'number'
        },

        dataZoom: [{
            type: 'inside',
            start:95,
            end: 100
        }, {
            start: 0,
            end: 100
        }],
        series: [{
            data: cell_data.map(function (row) {
                return row[1]
            }),
            label: {
                show: true,
                position: 'top'
            },
            type: 'bar',
            showBackground: true,
            backgroundStyle: {
                color: 'rgba(180, 180, 180, 0.2)'
            },
            itemStyle: itemStyle
        }]
    };
    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>
<script>
    function search_detail() {
        $("#all_marker_div").html(`
                <div  class="box box-color-3">
                    <div style="width: 100%;height: 400px;display: flex;justify-content: center;align-items:center;" id="all_marker"><i style="width: 26px;height: 38px;" class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
                </div>`);
        $("#marker_table_div").html(`
            <table id="marker_table" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                <tr>
                    <th>Gene name</th>
                    <th>Count</th>
                </tr>
                </thead>
            </table>`);

        let TissueType = document.getElementsByName("tis_type")[0].value
        let CellName = document.getElementsByName("cel_name")[0].value
        let selects = []
        let select = ""
        if (TissueType !== "")
            selects.push("TissueType='" + TissueType + "'")
        if (CellName !== "")
            selects.push("CellName='" + CellName + "'")
        if (selects.length > 0)
            select = "where " + selects.join(" and ");
        else
            select = "";
        $.ajax({
            url: "/<?php echo $web_title ?>/graph/detail_cell_markers.php",
            data: {select:select},
            type: 'post',
            dataType: "json"
        }).then(function (data) {
            $('#marker_table').DataTable({
                dom: '<"row"<"col-sm-6"iB><"col-sm-6"f>>rt<"row"<"col-sm-5"<"dataTables_info"l>><"col-sm-7"<"dataTables_paginate paging_full_numbers"p>>>',
                buttons: [{
                    extend: 'csvHtml5',
                    text: '<i class="ri-folder-download-line"></i>'
                }],
                order: [[1, "desc"]],
                data: data.map(function (row) {
                    return [row.name, row.weight]
                }),
                columns: [
                    {title: "Gene Name"},
                    {title: "Count"}
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).children('td').each(function (i, e) {
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
            window.chart = Highcharts.chart('all_marker', {
                series: [{
                    type: 'wordcloud',
                    data: data,
                    turboThreshold: 2000
                }],
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                }
            });
        })
    }

    setTimeout(function () {
        window.tis_type.val("Breast",search_tissue_cell);
        search_detail()
    }, 2000)
</script>
</html>
