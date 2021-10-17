<?php include ("public/public.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body data-target="#myScrollspy" data-spy="scroll">
<?php include ("public/header.php") ?>
<style>
    /* 元素 | http://www.licpathway.net/ReSNVdb/help.php */

    #body {
        width: 90%;
    }

    /* 内联 #5 | http://www.licpathway.net/ReSNVdb/help.php#information */

    h2 {
        font-weight: bold;
        border-bottom: 1px #6a737d solid;
        padding-bottom: 20px;
    }

    h2 + div {
        margin: 20px 20px;
    }

    h3 {
        margin: 20px 0;
    }

    .img-responsive {
        margin: 20px 0;
    }


    /* bootstrap.min-3.7.7.css | http://www.licpathway.net/ReSNVdb/public/css/bootstrap.min-3.7.7.css */

    img {
        width: 100%;
    }


    /* 元素 | http://www.licpathway.net/ReSNVdb/help.php#information */

    .nav-tabs {
        background: white;
    }

    p {
        font-size: 18px;
        line-height: 30px;
        letter-spacing: 0.039em;
        font-weight: 400;
        font-style: normal;
        margin: 20px 0 0px;
    }

    .p-1 {
        font-size: 16px;
        line-height: 1.7;
        color: #333;
    }

    .solid {
        width: 100%;
        background: #fff;
        /*-webkit-box-shadow: 0 1px 10px rgba(26, 26, 26, 0.9);*/
        /*box-shadow: 0 1px 10px rgba(26, 26, 26, 0.9);*/
        padding: 16px;
        margin-top: 20px;
        border: #6a737d 2px solid;
    }
    .dotted {
        border: #6a737d dotted 2px;
        padding: 16px;
        margin-top: 20px;
        background: #fff;
    }
    .red {
        border-color: red;
    }

    .tltle-red {
        color: red;
    }
    /* Custom Styles */
    ul.nav-tabs {
        /*width: 140px;*/
        margin-top: 20px;
        /*border-radius: 4px;*/
        border: 1px solid #ddd;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
    }

    ul.nav-tabs li {
        margin: 0;
        border-top: 1px solid #ddd;
    }

    ul.nav-tabs li:first-child {
        border-top: none;
    }

    ul.nav-tabs li a {
        margin: 0;
        padding: 4px 16px;
        border-radius: 0;
    }

    ul.nav-tabs li.active a, ul.nav-tabs li.active a:hover {
        color: #fff;
        background: #0088cc;
        border: 1px solid #0088cc;
    }

    ul.nav-tabs.affix {
        top: 72px; /* Set the top position of pinned element */
    }

    .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
        z-index: 0;
    }
    @media (max-width: 768px) {
        .affix {
            position: relative;
            top: 0;
        }
    }
</style>
<?php
include "public/conn_php.php";
$info_sql = "select GeneType,count(id) number from $main
            group by GeneType
            order by number asc";
$result = mysqli_query($conn, $info_sql);
while ($row = mysqli_fetch_assoc($result)) {
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
            <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator">Help</b></div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-3" id="myScrollspy">
            <ul class="nav nav-tabs nav-stacked" id="myScrollspyNav" data-spy="affix" data-offset-top="125">
                <li class="active"><a href="#information">1. What information is available in the <?php echo $web_title; ?>?</a></li>
                <li><a href="#content">2. Database content and construction</a></li>
                <li><a href="#use">3. How to use the <?php echo $web_title; ?>?</a></li>
                <li><a href="#Search">3.1. Search</a></li>
                <li><a href="#tissue_cell">3.1.1. Search by tissue and cell type</a></li>
                <li><a href="#gene">3.1.2. Search by gene name</a></li>
                <li><a href="#TF">3.1.3. Search by TFs related to Core transcriptional Regulatory Circuit</a></li>
                <li><a href="#Browse">3.2. Browse</a></li>
                <li><a href="#Submit">3.3. Submit</a></li>
                <li><a href="#Download">3.4. Download</a></li>
                <li><a href="#Explanation">4. Explanation of the definitions used by the website</a></li>
                <li><a href="#environment">4. Development environment</a></li>
            </ul>
        </div>
        <script>
            $("#myScrollspyNav").css("width", ($(".row:first-child")[0].clientWidth / 12 * 2.5) + "px");
            var up_to = function () {
                $("#context").animate({marginTop: 0}, 0);
                if (body.clientWidth > 893) {
                    $("#myScrollspyNav").css("width", ($(".row:first-child")[0].clientWidth / 12 * 2.5) + "px");
                } else {
                    $("#myScrollspyNav").css("width", "auto");
                }
            };

            //节流函数
            function throttle(lazyTime, Fuc) {
                var lastTime = null; //记录上次时间
                return function () {
                    var currentTime = new Date().getTime();
                    if (!lastTime) { //初始化时间
                        lastTime = currentTime;
                    }
                    if (lastTime + lazyTime < currentTime) {
                        Fuc();
                        lastTime = currentTime;
                    }
                }
            }

            window.onscroll = throttle(50, up_to); //事件回调是个闭包
        </script>
        <div class="col-lg-9" id="context">
            <div class="solid">
                <h2 id="information">1. What information is available in the <?php echo $web_title; ?>?</h2>
                <div>
                    <p>Transcription factors (TFs) recognize specific DNA sequences to control chromatin and transcription, forming a complex system that guides expression of the genome. Individual cells represent the basic blocks of tissues and organisms. Markers are signatures that help researchers to identify cell and tissue types. Many studies have confirmed that the regulation between TFs and related markers can drive cell differentiation and influence human diseases and biological processes. TFs coordinate cell type-specific transcriptional programs typically. Core transcriptional regulatory circuit (CRC) is comprised of a group of interconnected auto-regulating TFs forming loops, and core TFs in CRCs have been proved highly valuable for cell-type-specific transcriptional regulation in healthy and disease cells.Here, we established a comprehensive manually curated TFs, markers database for human (<?php echo $web_title; ?>, http://bio.liclab.net/<?php echo $web_title; ?>/index.php). Through reviewing <b style="color:green"><?php echo $info["PMID"]?></b> published literatures, the current release of <?php echo $web_title; ?> documents <b style="color:green">1,316</b> TFs, <b style="color:green">1,092</b> T Marker, <b style="color:green">473</b> I Marker , <b style="color:green">1,600</b>
                    TFMarker, <b style="color:green">1,424</b> TF Pmarker, involving <b style="color:green">383</b> cell types and <b style="color:green">95</b> tissue types for human. <?php echo $web_title; ?> will help identify specific cell types with signature genes such as transcription factors,markers and TFMarkers in analyzing sing-cell Sequencing data.</p>
                </div>
            </div>
            <div class="solid">
                <h2 id="content">2. Database content and construction</h2>
                <div>
                    <p>Through reviewing <b style="color:green"><?php echo $info["PMID"]?></b> published literature, we considered the genes manually curated to five types, according to their functions: 1) TFs: TFs regulate the expression of the markers; 2) T Marker: it is the marker which is regulated by the TFs; 3) I Marker: it is the marker which influences the activity of the TFs; 4) TFMarker: TFs play roles as markers;5) TF Pmarker: TFs play roles as potential markers. We manually examined TFs and related markers in each published paper, including <b style="color:green">1,316</b> TFs, <b style="color:green">1,092</b> T Marker, <b style="color:green">473</b> I Marker , <b style="color:green">1,600</b>
                    TFMarker, <b style="color:green">1,424</b> TF Pmarker, involving <b style="color:green">383</b> cell types and <b style="color:green">95</b> tissue types for human. We further collected the detailed information of these TFs and related markers, which were supported by strong experimental evidence including RNAi, in vitro knockdown, western blot, qRT-PCR, luciferase reporter assay and Immunofluorescent staining. <?php echo $web_title; ?> provides a user-friendly interface to browse, query, and visualize the detailed information about the TFs and related markers. </p>
                    <img class="dotted red" src="public/img/help/HELP流程图.png" class="img-responsive" alt=" ">
                </div>
            </div>
            <div class="solid">
                <h2 id="use">3. How to use the <?php echo $web_title; ?>?</h2>
                <div>
                    <div class="dotted">
                        <h3 id="Search">3.1. Search</h3>
                        <p>Users can search genes through four ways, including 'Search by tissue and cell type', 'Search by gene name' and 'Search by TFs related to Core transcriptional Regulatory Circuit'.  </p>
                        <img class="dotted red" src="public/img/help/search.png" class="img-responsive" alt=" ">
                        <div>
                            <h3 class="tltle-red" id="tissue_cell">3.1.1. Search by tissue and cell type</h3>
                            <p>Users searches for tissues and cell types, and <?php echo $web_title; ?> will return detailed information, including gene information in tissues and cell types, and text cloud pictures about gene distribution in tissues and cell types. </p>
                            <img class="dotted red" src="public/img/help/search_tissue_cell.png" class="img-responsive"
                                 alt=" ">
                            <img class="dotted red" src="public/img/help/search_tissue_cell_detail_table.png"
                                 class="img-responsive" alt=" ">
                            <img class="dotted red" src="public/img/help/search_tissue_cell_detail_graph.png"
                                 class="img-responsive" alt=" ">
                        </div>
                        <div>
                            <h3 class="tltle-red" id="gene">3.1.2. Search by gene name</h3>
                            <p>Users search for gene name, and <?php echo $web_title; ?> will return detailed information, including gene distribution in tissues and cell types, and gene expression in 'GTEx', 'CCLE', 'TCGA' and 'Encode Cell Lines'.</p>
                            <img class="dotted red" src="public/img/help/search_gene.png" class="img-responsive"
                                 alt=" ">
                            <p><?php echo $web_title; ?> will be automatically converted into a gene symbol if the user inputs the Ensembl ID/NCBI Refseq ID/Alias/Entrez Gene ID</p>
                            <img class="dotted red" src="public/img/help/search_gene_id_convert.png" class="img-responsive"
                                 alt=" ">
                            <img class="dotted red" src="public/img/help/search_gene_table.png"
                                 class="img-responsive" alt=" ">
                            <img class="dotted red" src="public/img/help/search_gene_graph_rect.png" class="img-responsive" alt=" ">
                            <img class="dotted red" src="public/img/help/search_gene_graph_dot.png" class="img-responsive" alt=" ">
                        </div>
                        <div>
                            <h3 class="tltle-red" id="TF">3.1.3. Search by TF related to Core transcriptional Regulatory Circuit</h3>
                            <p>Users search for TF related to Core transcriptional Regulatory Circuit, and <?php echo $web_title; ?> will return the TF information about CRC, including TF distribution in the tissue entered by user, and others related to TF downstream and upstream.</p>
                            <img class="dotted red" src="public/img/help/search_tf.png" class="img-responsive"
                                 alt=" ">
                            <img class="dotted red" src="public/img/help/search_tf_detail.png" class="img-responsive"
                                 alt=" ">
                            <img class="dotted red" src="public/img/help/search_tf_downstream.png" class="img-responsive" alt=" ">
                            <img class="dotted red" src="public/img/help/search_tf_upstream.png" class="img-responsive" alt=" ">
                        </div>
                    </div>
                    <div class="dotted">
                        <h3 id="Browse">3.2. Browse</h3>
                        <p>The 'Data-Browse' page is organized as an interactive and alphanumerically sortable table that allows users to quickly browse through 'Tissue type', 'Cell type' and 'Gene Type'. The 'Show entries' drop-down menu is provided for changing the number of records per page conveniently. </p>
                        <img class="dotted red" src="public/img/help/browse_all.png" class="img-responsive" alt=" ">
                        <img class="dotted red" src="public/img/help/browse_all_detail_info.png" class="img-responsive" alt=" ">
                        <img class="dotted red" src="public/img/help/browse_all_detail_graph.png" class="img-responsive" alt=" ">
                        <p>If the gene type is a TF, the graph will be displayed below.</p>
                        <img class="dotted red" src="public/img/help/browse_all_detail_tf_graph.png" class="img-responsive" alt=" ">
                        <p>On the 'Result of cell types', <?php echo $web_title; ?> will provide information about the genes in cells under the screening conditions of users, and the details page will show the specific sources of genes and some experimental information of corresponding articles.</p>
                        <img class="dotted red" src="public/img/help/browse_cell.png" class="img-responsive" alt=" ">
                        <img class="dotted red" src="public/img/help/browse_cell_info.png" class="img-responsive" alt=" ">
                        <img class="dotted red" src="public/img/help/browse_cell_table.png" class="img-responsive" alt=" ">
                        <img class="dotted red" src="public/img/help/browse_cell_source.png" class="img-responsive" alt=" ">
                    </div>
                    <div class="dotted">
                        <h3 id="Submit">3.3. Submit</h3>
                        <p>Users can submit 'Gene Name', 'Gene Type', 'Cell Name', 'Gene ID', 'Ensembl ID', 'Transcription factor Family', 'Tissue type', 'experiment name', 'Pubmed ID' and the description of TFs and related markers in the literature through the submit page.</p>
                        <img class="dotted red" src="public/img/help/submit.png" class="img-responsive" alt=" ">
                    </div>
                    <div class="dotted">
                        <h3 id="Download">3.4 Download</h3>
                        <p>The 'Download' page exhibits 'Gene Name', 'Gene Type', 'Cell Name', 'Gene ID', 'Ensembl ID', 'Transcription factor Family', 'Tissue type', 'experiment name', 'Pubmed ID' and the description of TFs and related markers in the literature for users to download. Moreover, the detailed description of file is also displayed.</p>
                    </div>
                </div>
            </div>
            <div class="solid">
                <h2 id="Explanation">4. Explanation of the definitions used by the website</h2>
                <div>
                    <table id="parameter_table" class="table table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Attribution</th>
                            <th>The description of the attribution</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "select * from $parameter";
                        $result = mysqli_query($conn, $sql);
                        while ($rows = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$rows["Attribution"]}</td>";
                            echo "<td>{$rows["The description of the attribution"]}</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="solid">
                <h2 id="environment">4. Development environment</h2>
                <div>
                    <p>Using MySQL 5.7.27, we developed the current version of <?php echo $web_title; ?> that runs on a Linux-based Apache
                        web
                        server. We utilized PHP 5.6.40 for sever-side scripting, Bootstrap v3.37 and JQuerry v2.1.1 for
                        interactive interface building, Echats for visualization. To
                        display
                        best, we recommend using a comprehensive web sever that supports HTML5 standard, for example,
                        Firefox, Google Chrome and Safari.
                    </p>
                    <p>The research community can access information freely in <?php echo $web_title; ?> database without registering or
                        logging in.
                        The web link of <?php echo $web_title; ?> is http://www.licpathway.net/<?php echo $web_title; ?>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "public/footer.php"; ?>
<script>
    $('.carousel').carousel({
        interval: 2000
    })
    $(document).ready(function () {
        $("#myScrollspy a").click(() => {
            $("#context").animate({marginTop: 0}, 0);
            setTimeout(function () {
                $("#context").animate({marginTop: 100}, 400);
            }, 50)
        });
    });
</script>
</body>
</html>
