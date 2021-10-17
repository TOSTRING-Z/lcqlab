<?php include("public.php") ?>
<?php include("link.php") ?>
<script>
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
    /**
     * archimedeanSpiral - Gives a set of cordinates for an Archimedian Spiral.
     *
     * @param {number} t How far along the spiral we have traversed.
     * @return {object} Resulting coordinates, x and y.
     */
    var archimedeanSpiral = function archimedeanSpiral(t) {
        t *= 0.1;
        return {
            x: t * Math.cos(t),
            y: t * Math.sin(t)
        };
    };
    Highcharts.seriesTypes.wordcloud.prototype.spirals.archimedean = archimedeanSpiral;
</script>
<header>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#example-navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/<?php echo $web_title ?>/index.php"><?php echo $web_title ?></a>
            </div>
            <div class="collapse navbar-collapse pull-right" id="example-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/<?php echo $web_title ?>/index.php">home</a></li>
                    <li><a href="/<?php echo $web_title ?>/browse.php">browse</a></li>
                    <li><a href="/<?php echo $web_title ?>/search.php">search</a></li>
                    <!--<li class="dropdown">
                        <a href="#/analysis" class="dropdown-toggle" data-toggle="dropdown">
                            analysis <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/<?php /*echo $web_title */?>/analysis_of_marker.php">Analysis of TFs related to CRC</a></li>
                            <li><a href="/<?php /*echo $web_title */?>/Pathway_enrichment_analysis_of_genes.php">Enrichment analysis of the pathway related to the target genes of core marker in CRC</a></li>
                        </ul>
                    </li>-->
                    <li><a href="/<?php echo $web_title ?>/statistics.php">statistics</a></li>
                    <li><a href="/<?php echo $web_title ?>/submit.php">submit</a></li>
                    <li><a href="/<?php echo $web_title ?>/download.php">download</a></li>
                    <li><a href="/<?php echo $web_title ?>/contact.php">contact</a>
                    <li><a href="/<?php echo $web_title ?>/help.php">help</a></li>
                </ul>
            </div>
        </div>
<!--        <div class="container-fluid header-bottom">-->
<!--            <div>-->
<!--                <div class="input-group">-->
<!--                <span class="input-group-addon">-->
<!--                <select class="form-control" name="Species">-->
<!--                    <option value="Human">Human</option>-->
<!--                    <option value="Mouse">Mouse</option>-->
<!--                </select>-->
<!--                </span>-->
<!--                    <span class="input-group-addon">-->
<!--                <input type="text" name="gene" class="form-control" placeholder="Quick search a Gene, eg. CREB1.">-->
<!--                </span>-->
<!--                    <span class="input-group-addon">-->
<!--                    <button class="btn btn-default" type="submit">Search</button>-->
<!--                </span>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div>-->
<!--            </div>-->
<!--        </div>-->
    </nav>
</header>
<script src="/<?php echo $web_title ?>/public/js/public.js"></script>
