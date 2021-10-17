<?php
include "public.php";
$conn = mysqli_connect("39.98.139.1","root","hmudq?lcq#_>123987Zz");
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
header("Content-type: text/html;charset=utf-8");//防止乱码
mysqli_query($conn,'SET NAMES UTF8');
//TF-Marker
$main = "`$web_title`.main";
$TFs = "`$web_title`.TFs";
$parameter = "`$web_title`.parameter";
$pathway = "`$web_title`.pathway";
$hg19_refseq = "`$web_title`.hg19_refseq";
//crcdb
$tf_mrna_manual = "crcdb.tf_mrna_manual";
$tf_micrna = "crcdb.tf_micrna";
$tf_lncrna_predict = "crcdb.tf_lncrna_predict";
$tf_information = "crcdb.tf_information";
$data_coltron_tf_num = "crcdb.data_coltron_tf_num";
$sample_id = "crcdb.sample_id";
$data_crc_mapper_tf_num = "crcdb.data_crc_mapper_tf_num";
$data_coltron_tf2se = "crcdb.data_coltron_tf2se";
$tf_disease = "crcdb.tf_disease";
$data__first_crc = "crcdb.data_%s_first_crc";
$data__tf_num = "crcdb.data_%s_tf_num";
//LncRNA
$idConvert = "cjx.idConvert";
//ENdb
$enhancer_main = "ENdb.enhancer_main";
//KnockTF
$figure_tf_expression = "tfkk.figure_tf_expression";
$Cancer_CellLines_CCLE_gene_Exp_final_ok = "tfkk.Cancer_CellLines_CCLE_gene_Exp_final_ok";
$ENCODE_Cellline_Gene_EXP_FPKM_ok = "tfkk.ENCODE_Cellline_Gene_EXP_FPKM_ok";
$Normal_tissue_GTEX_TF_Exp_tpm_final_ok = "tfkk.Normal_tissue_GTEX_TF_Exp_tpm_final_ok";

/* FUNCTION */
function get_symbol($GeneName){
    global $idConvert,$main,$conn;
    $len = mysqli_num_rows(mysqli_query($conn, "select GeneName
        from $main
        where GeneName='$GeneName'"));
    if($len > 0){
        return null;
    }
    $data = mysqli_fetch_all(mysqli_query($conn, "select symbol,
        group_concat(DISTINCT alias_symbol SEPARATOR ', ') alias_symbol,  
        group_concat(DISTINCT ensembl_id SEPARATOR ', ') ensembl_id,  
        group_concat(DISTINCT gene_id SEPARATOR ', ') gene_id,  
        group_concat(DISTINCT accession SEPARATOR ', ') accession
        from $idConvert
        where symbol='$GeneName'
        or alias_symbol='$GeneName'
        or ensembl_id='$GeneName'
        or gene_id='$GeneName'
        or accession='$GeneName'
        group by symbol"));
    return ($data==Array()?null:$data[0][0]==$GeneName?null:$data);
}
?>
