<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>detail</title>
    <?php include("../public/link.php") ?>
</head>
<?php
include '../public/public.php';
include '../public/conn_php.php';
$tables = ["panqi.GTEX_v7_gene_id","panqi.haploReg_final2","panqi.new_PancanQTL_final"];
$title = isset($_GET["title"])?$_GET["title"]:"GTEX_v7_gene_id";
$GeneName = $_GET["GeneName"];
$list = range(1, 22, 1) + ["X", "Y"];
foreach ($list as $i) {
    $chr = "chr" . $i;
    mysqli_query($conn, "create temporary table panqi.tem (
                        select Rsid
                        from panqi.Genemapper_{$chr}_final
                        where Closest_gene = '$GeneName'
                    )");
    mysqli_query($conn, "INSERT INTO panqi.tem (
                        select rsID
                        from panqi.summary_{$chr}_id
                        where gene = '$GeneName'
                    )");
}
$Rsids = "(select rsID from panqi.tem)";
?>
<div id="loading" class="loading">
    <i class="ri-loader-2-line rotateIn"></i>
</div>
<div class="dropdown">
    Data source of:
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
        <b id="title">
            <?php
            if($title=="GTEX_v7_gene_id") echo 'GTEX v7';
            if($title=="haploReg_final2") echo 'HaploReg v4.1';
            if($title=="new_PancanQTL_final") echo 'PancanQTL cis-eQTL';
            ?>
        </b>
        <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
        <a href="?GeneName=<?php echo $GeneName ?>&title=GTEX_v7_gene_id">GTEX v7</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=haploReg_final2">HaploReg v4.1</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=new_PancanQTL_final">PancanQTL cis-eQTL</a>
    </div>
</div>

<div>
    <?php if($title=="GTEX_v7_gene_id") { ?>
    <div id="GTEX_v7_gene_id">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Gene_id</th>
                <th>Gene_name</th>
                <th>Tissue_name</th>
                <th>TSS_distance</th>
                <th>Ma_samples</th>
                <th>MA_count</th>
                <th>Maf</th>
                <th>Pval_nominal</th>
                <th>Slope</th>
                <th>Slope_se</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[0] where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                $symbol = [];
                foreach (preg_split("/,/i",$rows["Gene_id"]) as $geneid){
                    $ensembl_id = preg_split("/\./i",$geneid)[0];
                    $result1=mysqli_query($conn,"select symbol from panqi.gene_convert where ensembl_id = '$ensembl_id'");
                    $row = mysqli_fetch_assoc($result1);
                    $symbol[] = $row['symbol'];
                }
                $symbol = join(",",$symbol);

                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Gene_id"]}</td>";
                echo "<td>{$symbol}</td>";
                echo "<td>{$rows["Tissue_name"]}</td>";
                echo "<td>{$rows["TSS_distance"]}</td>";
                echo "<td>{$rows["Ma_samples"]}</td>";
                echo "<td>{$rows["MA_count"]}</td>";
                echo "<td>{$rows["Maf"]}</td>";
                echo "<td>{$rows["Pval_nominal"]}</td>";
                echo "<td>{$rows["Slope"]}</td>";
                echo "<td>{$rows["Slope_se"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="haploReg_final2") { ?>
    <div id="haploReg_final2">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Chr</th>
                <th>Position</th>
                <th>Gene</th>
                <th>Tissue</th>
                <th>Pvalue</th>
                <th>Data_source</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[1] where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Chr"]}</td>";
                echo "<td>{$rows["Position"]}</td>";
                echo "<td>{$rows["Gene_name"]}</td>";
                echo "<td>{$rows["Tissue"]}</td>";
                echo "<td>{$rows["Pvalue"]}</td>";
                echo "<td>{$rows["Data_source"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="new_PancanQTL_final") { ?>
    <div id="new_PancanQTL_final">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Chr</th>
                <th>Position</th>
                <th>Gene_name</th>
                <th>Cancer</th>
                <th>Beta</th>
                <th>T_stat</th>
                <th>Pvalue</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[2] where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Chr"]}</td>";
                echo "<td>{$rows["Position"]}</td>";
                echo "<td>{$rows["Gene_name"]}</td>";
                echo "<td>{$rows["Cancer"]}</td>";
                echo "<td>{$rows["Beta"]}</td>";
                echo "<td>{$rows["T_stat"]}</td>";
                echo "<td>{$rows["Pvalue"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
<script src="../public/js/ifram.js"></script>
</body>
</html>