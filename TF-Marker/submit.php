<?php include (__DIR__."/public/public.php");
ini_set("error_reporting", "E_ALL & ~E_NOTICE");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_title ?></title>
</head>
<body>
<?php include (__DIR__."/public/header.php") ?>
<style>
    input.form-control {
        width: 100%;
        height: 35px;
    }
</style>

<div class="container" id="body">
    <form action="submit.php" method="post">
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator">Submit</b></div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="box box-color-1">
                <center>
                    <h1><center><b>Submit</b></center></h1>
                    <h4 style="line-height: 2;">
                        If you want to share your data, please fill out the necessary information in the form below and we will add it to <?php echo $web_title?> soon. Thank you.
                    </h4>
                </center>
                </div>
                <div class="box box-color-4">
                <h2><b>Submitter Information</b></h2>
                <div class="aler" role="alert">

                    <div class="form-group">
                        <label class="control-label">Your Name</label>
                        <input type="text" class="form-control" name="user_name" placeholder="Non-essential option">
                    </div>

                    <div class="form-group">
                        <label class="control-label">E-mail</label>
                        <input type="email" id="user_mail" class="form-control" oninput="check_email(this)" name="user_mail" placeholder="It is necessary">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Other description</label>
                        <input type="text" class="form-control" name="description" placeholder="Non-essential option">
                    </div>
                </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box box-color-4">
                <h2><b>TF/Marker Information</b></h2>
                <div class="aler" role="alert">

                    <div class="form-group">
                        <label class="control-label">Gene name</label>
                        <input type="text" id="Gene_name" class="form-control" oninput="check(this)" name="Gene_name" placeholder="It is necessary,eg.TP53.">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Experimental name </label>
                        <input type="text" id="Experimental_name" class="form-control" oninput="check(this)" name="Experimental_name" placeholder="It is necessary,eg.PCR;Weston blot.">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description of Gene </label>
                        <input type="text" id="Description_of_Gene" class="form-control" oninput="check(this)" name="Description_of_Gene" placeholder="It is necessary,eg.Paraffin-embedded liver biopsies from pediatric patients with ...">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Interacting Gene </label>
                        <input type="text" id="Interacting_Gene" class="form-control"  name="Interacting_Gene" placeholder="Non-essential option,eg.GATA3(TFMarker).">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Experiment Type</label>
                        <input type="text" class="form-control" name="Experiment_Type" placeholder="Non-essential option,eg.low.">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tissue Type</label>
                        <input type="text" id="tissue_type" class="form-control" name="tissue_type" oninput="check(this)" placeholder="It is necessary,eg.Liver.">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Cell Type</label>
                        <input type="text" id="Cell_Type" class="form-control" oninput="check(this)" name="Cell_Type" placeholder="It is necessary,eg.inflammatory cell.">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Cell Name</label>
                        <input type="text" id="Cell_Name" class="form-control" oninput="check(this)" name="Cell_Name" placeholder="It is necessary,eg.inflammatory cell.">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Pubmed ID</label>
                        <input type="text" id="pubmed_id" class="form-control" name="pubmed_id" oninput="check(this)" name="pubmed_id" placeholder="It is necessary,eg.29486229.">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="Submit" onclick="return check_all()" class="btn btn-primary">Submit</button>
                        <button type="Reset" class="btn btn-primary">Reset</button>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </form>
</div>
<script>
    <?php
    if(isset($_POST["user_mail"]))
        echo "alert('{$_REQUEST["user_mail"]}, thank you for your submission!');";
    ?>
    var user_mail=document.getElementById("user_mail");
    var Gene_name=document.getElementById("Gene_name");
    var Experimental_name=document.getElementById("Experimental_name");
    var Description_of_Gene=document.getElementById("Description_of_Gene");
    var Cell_Name=document.getElementById("Cell_Name");
    var Cell_Type=document.getElementById("Cell_Type");
    var tissue_type=document.getElementById("tissue_type");
    var pubmed_id=document.getElementById("pubmed_id");

    user_mail.setCustomValidity("Please enter a mailbox address!");
    Gene_name.setCustomValidity("It is necessary!");
    Experimental_name.setCustomValidity("It is necessary!");
    Description_of_Gene.setCustomValidity("It is necessary!");
    Cell_Name.setCustomValidity("It is necessary!");
    Cell_Type.setCustomValidity("It is necessary!");
    tissue_type.setCustomValidity("It is necessary!");
    pubmed_id.setCustomValidity("It is necessary!");
    function check_email(e){
        e.setCustomValidity("");
        if (e.checkValidity() == false)
            e.setCustomValidity("Please enter a mailbox address!");
        else
            e.setCustomValidity("");
    }
    function check(e){
        if (e.value == "")
            e.setCustomValidity("It is necessary!");
        else
            e.setCustomValidity("");
    }
    function check_all(){
        var user_mail=document.getElementById("user_mail");
        var Gene_name=document.getElementById("Gene_name");
        var Experimental_name=document.getElementById("Experimental_name");
        var Description_of_Gene=document.getElementById("Description_of_Gene");
        var Cell_Name=document.getElementById("Cell_Name");
        var Cell_Type=document.getElementById("Cell_Type");
        var tissue_type=document.getElementById("tissue_type");
        var pubmed_id=document.getElementById("pubmed_id");

        user_mail.setCustomValidity("");
        Gene_name.setCustomValidity("");
        Experimental_name.setCustomValidity("");
        Description_of_Gene.setCustomValidity("");
        Cell_Name.setCustomValidity("");
        Cell_Type.setCustomValidity("");
        tissue_type.setCustomValidity("");
        pubmed_id.setCustomValidity("");

        if (user_mail.checkValidity() == false||user_mail.value == "")
            user_mail.setCustomValidity("Please enter a mailbox address!");
        else
            user_mail.setCustomValidity("");
        if (Gene_name.value == "")
            Gene_name.setCustomValidity("It is necessary!");
        else
            Gene_name.setCustomValidity("");
        if (Experimental_name.value == "")
            Experimental_name.setCustomValidity("It is necessary!");
        else
            Experimental_name.setCustomValidity("");
        if (Description_of_Gene.value == "")
            Description_of_Gene.setCustomValidity("It is necessary!");
        else
            Description_of_Gene.setCustomValidity("");
        if (Cell_Name.value == "")
            Cell_Name.setCustomValidity("It is necessary!");
        else
            Cell_Name.setCustomValidity("");
        if (Cell_Type.value == "")
            Cell_Type.setCustomValidity("It is necessary!");
        else
            Cell_Type.setCustomValidity("");
        if (tissue_type.value == "")
            tissue_type.setCustomValidity("It is necessary!");
        else
            tissue_type.setCustomValidity("");
        if (pubmed_id.value == "")
            pubmed_id.setCustomValidity("It is necessary!");
        else
            pubmed_id.setCustomValidity("");
    }
</script>
<?php include "public/footer.php"; ?>
</body>
</html>

