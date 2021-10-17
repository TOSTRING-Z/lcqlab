<?php
include '../public/public.php';
include("../public/conn_php.php");
$GeneName = $_POST["GeneName"];
$sample_table_sql = "SELECT distinct
                    Enhancer_id,
                    Species,
                    Enhancer_symbol,
                    Chromosome,
                    Start_position,
                    End_position,
                    Biosample_name,
                    Disease,
                    Enhancer_experiment
                    from $enhancer_main
                    left join $hg19_refseq
                    on GeneName = '$GeneName'
                    and (Start_position >= txStart and txStart <= End_position
                    or txStart >= Start_position and Start_position <= txEnd)";
$sample_table_result = mysqli_query($conn, $sample_table_sql);
while ($rows = mysqli_fetch_assoc($sample_table_result)) {
    $data[] = [
        htmlentities($rows["Enhancer_id"]),
        htmlentities($rows["Enhancer_symbol"]),
        htmlentities("{$rows["Chromosome"]}:{$rows["Start_position"]}~{$rows["End_position"]}"),
        htmlentities($rows["Biosample_name"]),
        htmlentities($rows["Disease"]),
        htmlentities($rows["Enhancer_experiment"])
    ];
}
echo json_encode(array(
    "data" => $data
),JSON_UNESCAPED_UNICODE);