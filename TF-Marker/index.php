<?php include("public/public.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body>
<?php include("public/header.php") ?>
<style>
    img[data-name] {
        display: inline-block;
        margin: auto;
        height: 5rem;
        object-fit: contain;
        cursor: pointer;
        width: 367px;
    }

    p {
        text-align: justify;
        text-justify: distribute-all-lines;
        text-align-last: left;
        -moz-text-align-last: left; /* 针对 Firefox 的代码 */
        word-break: break-word;
        -webkit-hyphens: manual;
        -ms-hyphens: manual; /*ie10+*/
        hyphens: manual;
        margin: 10px 0;
    }

    .p-1 {
        font-size: 16px;
        line-height: 1.7;
        color: #333;
    }


    .carousel-inner > .item > a > img, .carousel-inner > .item > img, .img-responsive, .thumbnail a > img, .thumbnail > img {
        height: 56rem;
    }

    .box .info a {
        cursor: pointer;
    }

    /* style.css | http://www.licpathway.net/TF-Marker/public/css/style.css */

    a {
        display: block;
    }

    /* bootstrap.min.css | https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css */

    a {
        /* color: #337ab7; */
        color: white;
    }

    a:focus, a:hover {
        /* color: #23527c; */
        /* text-decoration: underline; */
        color: #ff7f00;
        text-decoration: none;
    }

    .sister a {
        color: #3192e6;
        font-weight: bold;
        font-size: medium;
        display: inline-block;
    }

    .sister p {
        font-size: larger;
    }
</style>
<?php
include "public/conn_php.php";
$info_sql = "select GeneType,count(id) number from $main
            group by GeneType
            order by number asc";
$result = mysqli_query($conn, $info_sql);
$data[] = ["number", "GeneType"];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [$row["number"], $row["GeneType"]];
    $info[$row["GeneType"]] = $row["number"];
}
$info_sql = "select count(*) number from (select distinct PMID from $main) tem";
$result = mysqli_query($conn, $info_sql);
$row = mysqli_fetch_assoc($result);
$info["PMID"] = $row["number"];
$info_sql = "select count(*) number from (select distinct CellName from $main) tem";
$result = mysqli_query($conn, $info_sql);
$row = mysqli_fetch_assoc($result);
$info["CellName"] = $row["number"];
$info_sql = "select count(*) number from (select distinct TissueType from $main) tem";
$result = mysqli_query($conn, $info_sql);
$row = mysqli_fetch_assoc($result);
$info["TissueType"] = $row["number"];
?>
<div class="container" id="body">
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator"><?php echo $web_title ?></b>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-6">
            <div>
                <div class="row">
                    <div class="col-lg-12">
                        <h4 style="text-align: center"><b
                                    style="color:#4a2d8d;font-size: 40px;"><?php echo $web_title ?>!</b>v1.0</h4>
                        <h4 style="text-align: center;font-size: 20px">A <?php echo $web_title ?>
                            <b>d</b>ata<b>b</b>ase for human
                        </h4>
                    </div>
                </div>
                <div id="myCarousel" style="margin-top:1em" class="carousel slide">
                    <?php
                    function get_file_list($path)
                    {
                        $file_list = [];
                        $file_path = $path;
                        if (is_dir($file_path)) {
                            $handler = opendir($file_path);
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $file_list[] = $filename;
                                }
                            }
                            closedir($handler);
                            return $file_list;
                        }
                    }

                    ?>
                    <!-- 轮播（Carousel）项目 -->
                    <div class="carousel-inner">
                        <?php
                        $path = "public/img/home/Carousel/";
                        foreach (get_file_list($path) as $i => $val) { ?>
                            <div class="item <?php echo $i == 0 ? "active" : "" ?>">
                                <img src="<?php echo "$path$val" ?>" style="width: 100%">
                            </div>
                        <?php } ?>
                    </div>
                    <!-- 轮播（Carousel）导航 -->
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div>

                <h3 style="text-align: center"><b>What is <?php echo $web_title ?>?</b></h3>

                <p class="p-1" style="font-size:18px; ">
                    Here, we developed the TF-Marker database (TF-Marker, http://bio.liclab.net/TF-Marker/), aiming to
                    provide a comprehensive manually curated TFs and related markers in specific cell and tissue types
                    for human. By curating thousands of published literature, <b style="color:green">5,905</b> entries including the TFs and
                    related markers information were manually curated.and classified into five types according to their
                    functions: 1) TF: TFs which regulate the expression of the markers; 2) T Marker: markers which are
                    regulated by the TF; 3) I Marker: markers which influence the activity of TFs; 4) TFMarker: TFs
                    which play roles as markers; 5) TF Pmarker: TFs which play roles as potential markers. The <b style="color:green">5,905</b>
                    entries in current version of TF-Marker includes <b style="color:green">1,316</b> TFs, <b style="color:green">1,092</b> T Marker, <b style="color:green">473</b> I Marker , <b style="color:green">1,600</b>
                    TFMarker, <b style="color:green">1,424</b> TF Pmarker, involving <b style="color:green">383</b> cell types and <b style="color:green">95</b> tissue types for human. TF-Marker
                    further provides a user-friendly interface to browse, query and visualize the detailed information
                    about TFs and related markers. TF-Marker is a valuable resource to understand the regulation
                    patterns of different tissues and cells.
                </p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <center><h1><b>Quick search</b></h1></center>
            <br></div>
        <div class="col-lg-6">
            <div class="box box-color-3" id="right-1">
                <img src="public/img/home/GENETYPE123.svg" width="100%" height="262px">
                <div class="info">
                    <a href="search.php?gene_type=TF">
                        <p>Type 1(<strong style="color: #ff7f00">TF</strong>):
                            TF: the <strong>TF</strong> <strong>regulates</strong> the expression of the
                            <strong>markers</strong>
                        </p>
                    </a>
                    <a href="search.php?gene_type=T Marker">
                        <p>Type 2(<strong style="color: #ff7f00">T Marker</strong>):
                            the <strong>marker</strong> which is <strong>regulated</strong> by the <strong>TF</strong>
                        </p>
                    </a>
                    <a href="search.php?gene_type=I Marker">
                        <p>Type 3(<strong style="color: #ff7f00">I Marker</strong>):
                            the <strong>marker</strong> which <strong>influences</strong> the activity of the
                            <strong>TF</strong>
                        </p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-color-3" height-to="right-1">
                <img src="public/img/home/genetype5.svg" width="100%" height="262px">
                <div class="info box-bottom">
                    <a href="search.php?gene_type=TF Pmarker">
                        <p>Type 5(<strong style="color: #ff7f00">TF Pmarker</strong>):
                            <strong>TFs</strong> play roles as <strong>potential markers</strong>
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-color-3" id="right-2">
                <img src="public/img/home/genetype4.svg" width="100%" height="262px">
                <div class="info">
                    <a href="search.php?gene_type=TFMarker">
                        <p>Type 4(<strong style="color: #ff7f00">TFMarker</strong>):
                            <strong>TFs</strong> play roles as <strong>markers</strong>
                        </p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box box-color-3" height-to="right-2">
                <a href="search.php"><img src="public/img/home/Tissue;cell_cjx.svg" width="100%" height="262px"></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <hr>
            <center><h1><b>Gene Type</b></h1></center>
            <div id="gene_type_graph"
                 style="width:100%;height:300px;display: flex;justify-content: center;align-items:center;"><i
                        style="width: 26px;height: 38px;"
                        class="ri-refresh-fill animate__animated animate__rotateOut"></i></div>
        </div>
        <div class="col-lg-6">
            <hr>
            <center><h1><b>Visitors</b></h1></center>
            <script type="text/javascript"
                    src="//rf.revolvermaps.com/0/0/6.js?i=5oy9ffarymk&amp;m=2&amp;c=ff0000&amp;cr1=ffffff&amp;f=ubuntu&amp;l=1"
                    async="async"></script>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <hr>
            <div style="font-size: large">
                <center><h1><b>News and Updates</b></h1></center>
                <p><i class="ri-map-pin-time-fill"></i></span>&nbsp;<strong>01/16/2021 Database construction </strong>
                </p>
                <p><i class="ri-map-pin-time-fill"></i></span>&nbsp;<strong>04/11/2021 The database is online </strong>
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <hr>
            <div style="font-size: large">
                <center><h1><b>Publication</b></h1></center>
            </div>
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="sister">
            <center><h1><b><i class="ri-heart-line"></i> Sister Projects</h1></b></center>
            <div class="col-lg-6">
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://www.licpathway.net/ENdb/index.php">ENdb</a>
                    : an experimentally supported enhancer database for human and mouse
                </p>

                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a
                            href="http://www.licpathway.net/SEanalysis/?tdsourcetag=s_pctim_aiomsg">SEanalysis</a>
                    : a web tool for super-enhancer associated regulatory analysis
                </p>
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://bio.liclab.net/LncSEA/index.php">LncSEA</a>
                    : a comprehensive human lncRNA sets resource and enrichment analysis platform
                </p>
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://www.licpathway.net/ATACdb">ATACdb</a>
                    : A comprehensive human chromatin accessibility database
                </p>

            </div>
            <div class="col-lg-6">
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://www.licpathway.net/KnockTF/">KnockTF</a>
                    : a comprehensive human gene expression profile database with knockdown/knockout of transcription
                    factors
                </p>
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a
                            href="http://www.licpathway.net/TRCirc/view/index">TRCirc</a>
                    : a resource for transcriptional regulation information of circRNAs
                </p>
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://www.licpathway.net/TRlnc/view/index">TRlnc</a>
                    : a comprehensive database of human transcriptional regulation of lncRNAs
                </p>
                <p>
                    <i class="ri-thumb-up-fill"></i>&nbsp;<a href="http://www.licpathway.net/VARAdb/">VARAdb</a>
                    : a variation annotation database for human

                </p>
            </div>

        </div>
    </div>
</div>
<?php include "public/footer.php"; ?>
</body>
<script>
    var data = <?php echo json_encode($data)?>;
    data = [
        [473, 'I Marker'],[1092, 'T Marker'],[1316, 'TF'],[1424, 'TF Pmarker'],[1600, 'TFMarker']
    ]
    var dom = document.getElementById("gene_type_graph");
    var gene_type_graph = echarts.init(dom);
    option = {
        dataset: {
            source: data
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        grid: {containLabel: true},
        xAxis: {
            name: 'number',
            nameLocation: 'center',
            nameGap: 30
        },
        yAxis: {type: 'category'},
        series: [
            {
                type: 'bar',
                encode: {
                    // Map the "amount" column to X axis.
                    x: 'number',
                    // Map the "product" column to Y axis
                    y: 'GeneType'
                },
                itemStyle: itemStyle
            }
        ]
    };

    gene_type_graph.setOption(option, true);
</script>
</html>
