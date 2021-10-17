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
$tables = ["panqi.gwas_catalog_2019_hg19_ucsc","panqi.gwasdb_20150819_snp_trait","panqi.GADCDC","panqi.OADGAR","panqi.grasp_process"];
$title = isset($_GET["title"])?$_GET["title"]:"gwas_catalog_2019_hg19_ucsc";
?>
<div id="loading" class="loading">
    <i class="ri-loader-2-line rotateIn"></i>
</div>
<div class="dropdown">
    Data source of:
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
        <b id="title">
        <?php
        if($title=="gwas_catalog_2019_hg19_ucsc") echo 'GWAS Catalog 2019';
        if($title=="gwasdb_20150819_snp_trait") echo 'GWASdb 2.0';
        if($title=="GADCDC") echo 'GAD';
        if($title=="OADGAR") echo 'Johnson and O\'Donnell';
        if($title=="grasp_process") echo 'GRASP';
        ?>
        </b>
        <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
        <a href="?GeneName=<?php echo $GeneName ?>&title=gwas_catalog_2019_hg19_ucsc">GWAS Catalog 2019</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=gwasdb_20150819_snp_trait">GWASdb 2.0</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=GADCDC">GAD</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=OADGAR">Johnson and O'Donnell</a>
        <a href="?GeneName=<?php echo $GeneName ?>&title=grasp_process">GRASP</a>
    </div>
</div>
<div>
    <?php if($title=="gwas_catalog_2019_hg19_ucsc") { ?>
    <div id="gwas_catalog_2019_hg19_ucsc">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Chr</th>
                <th>Position</th>
                <th>Disease/Trait</th>
                <th>Reported_genes</th>
                <th>P_value</th>
                <th>PubmedID</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[0] where Rsid in $Rsids";
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Chr"]}</td>";
                echo "<td>{$rows["Position"]}</td>";
                echo "<td>{$rows["Disease_Trait"]}</td>";
                echo "<td>{$rows["Reported_Gene_s"]}</td>";
                echo "<td>{$rows["P_value"]}</td>";
                echo "<td><a target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["Pubmedid"]}\">{$rows["Pubmedid"]}</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="gwasdb_20150819_snp_trait") { ?>
    <div id="gwasdb_20150819_snp_trait">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Chr</th>
                <th>Position</th>
                <th>Allele</th>
                <th>P_value</th>
                <th>Gwas_trait</th>
                <th>Gene_symbol</th>
                <th>Variant_type</th>
                <th>PubmedID</th>
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
                echo "<td>{$rows["Ref"]} > {$rows["Alt"]}</td>";
                echo "<td>{$rows["P_value"]}</td>";
                echo "<td>{$rows["Gwas_trait"]}</td>";
                echo "<td>{$rows["Gene_symbol"]}</td>";
                echo "<td>{$rows["Variant_type"]}</td>";
                echo "<td><a target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["Pubmedid"]}\">{$rows["Pubmedid"]}</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="GADCDC") { ?>
    <div id="GADCDC">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Gene</th>
                <th>Disease</th>
                <th>Disease class</th>
                <th>PubmedID</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[2] where rsID in $Rsids";
            
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["rsID"]}</td>";
                echo "<td>{$rows["Gene"]}</td>";
                echo "<td>{$rows["Disease"]}</td>";
                echo "<td>{$rows["Disease_class"]}</td>";
                echo "<td><a target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["Pubmedid"]}\">{$rows["Pubmedid"]}</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="OADGAR") { ?>
    <div id="OADGAR">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Primary_phenotype</th>
                <th>P_value</th>
                <th>Genes</th>
                <th>Validation</th>
                <th>PubmedID</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[3] where rsID in $Rsids";
            
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["rsID"]}</td>";
                echo "<td>{$rows["Primary_phenotype"]}</td>";
                echo "<td>{$rows["P_value"]}</td>";
                echo "<td>{$rows["Gene(s)"]}</td>";
                echo "<td>{$rows["Validation"]}</td>";
                echo "<td><a target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["Pubmedid"]}\">{$rows["Pubmedid"]}</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if($title=="grasp_process") { ?>
    <div id="grasp_process">
        <table width="100%" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>rsID</th>
                <th>Chr</th>
                <th>Position</th>
                <th>P_value</th>
                <th>PubmedID</th>
                <th>Phenotype</th>
                <th>Phenotype_description</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "select * from $tables[4] where Rsid in $Rsids";
            
            $result=mysqli_query($conn,$sql);
            while($rows = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$rows["Rsid"]}</td>";
                echo "<td>{$rows["Chr"]}</td>";
                echo "<td>{$rows["Position"]}</td>";
                echo "<td>{$rows["P_value"]}</td>";
                echo "<td><a target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/{$rows["Pubmedid"]}\">{$rows["Pubmedid"]}</a></td>";
                echo "<td>{$rows["Phenotype"]}</td>";
                echo "<td>{$rows["Phenotype_escription"]}</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
</body>
<script src="../public/js/ifram.js"></script>
</html>