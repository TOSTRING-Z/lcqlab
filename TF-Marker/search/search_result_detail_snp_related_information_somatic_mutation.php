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
$tables = ["panqi.gj2_Oncobase_SM_TCGA3","panqi.gj2_Oncobase_SM_ICGC"];
$title = isset($_GET["title"])?$_GET["title"]:"gj2_Oncobase_SM_TCGA3";
?>
<div id="loading" class="loading">
    <i class="ri-loader-2-line rotateIn"></i>
</div>
<div class="dropdown">
    Data source of:
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
        <b id="title">
            <?php
            if($title=="gj2_Oncobase_SM_TCGA3") echo 'Somatic mutation of OncoBase TCGA';
            if($title=="gj2_Oncobase_SM_ICGC") echo 'Somatic mutation of OncoBase ICGC';
            ?>
        </b>
        <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
        <a href="?GeneName=<?php echo $GeneName ?>&&title=gj2_Oncobase_SM_TCGA3">Somatic mutation of OncoBase TCGA</a>
        <a href="?GeneName=<?php echo $GeneName ?>&&title=gj2_Oncobase_SM_ICGC">Somatic mutation of OncoBase ICGC</a>
    </div>
</div>
<div>
    <?php if($title=="gj2_Oncobase_SM_TCGA3") { ?>
    <div id="gj2_Oncobase_SM_TCGA3">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Allele</th>
                <th>Gene_region</th>
                <th>Gene_symbol</th>
                <th>Effect</th>
                <th>TCGA_occurance</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[0] 
                    where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Ref"]} > {$rows["Alt"]}</td>";
                echo "<td>{$rows["Gene_region"]}</td>";
                echo "<td>{$rows["Gene_symbol"]}</td>";
                echo "<td>{$rows["Effect"]}</td>";
                echo "<td>{$rows["TCGA_occurance"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="gj2_Oncobase_SM_ICGC") { ?>
    <div id="gj2_Oncobase_SM_ICGC">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Allele</th>
                <th>Gene_region</th>
                <th>Gene_symbol</th>
                <th>Effect</th>
                <th>ICGC_occurance</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[1] 
            where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Ref"]} > {$rows["Alt"]}</td>";
                echo "<td>{$rows["Gene_region"]}</td>";
                echo "<td>{$rows["Gene_symbol"]}</td>";
                echo "<td>{$rows["Effect"]}</td>";
                echo "<td>{$rows["TCGA_occurance"]}</td>";
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