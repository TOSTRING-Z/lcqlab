<?php include (__DIR__."/public/public.php") ;
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
<div class="container" id="body">
    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <div class="pull-right"><i class="ri-map-pin-line"></i> <b class="navigator">Download</b></div>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <i class="ri-file-download-line"></i> Download
                </h3>
            </div>
            <div class="panel-body">
                <table id="downloapTable" style="border-spacing: 2px; border-color: grey;border-collapse: collapse;" class="table hovered">
                    <thead><tr><th style="border-top: 0px solid #e9ecef;">FileName</th><th style="border-top: 0px solid #e9ecef;">Description</th><th class="spa"  style="border-top: 0px solid #e9ecef;">Download</th></tr></thead>
                    <tr>
                        <td>All TF-markers</td>
                        <td>All TF-markers of human.</td>
                        <td><a download="main.txt" href="public/download/main_download.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                    <tr>
                        <td>I Marker</td>
                        <td>Gene type is I Marker.</td>
                        <td><a download="I Marker.txt" href="public/download/download_I%20Marker.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                    <tr>
                        <td>T Marker</td>
                        <td>Gene type is T Marker.</td>
                        <td><a download="T Marker.txt" href="public/download/download_T%20Marker.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                    <tr>
                        <td>TF</td>
                        <td>Gene type is TF.</td>
                        <td><a download="TF.txt" href="public/download/download_TF.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                    <tr>
                        <td>TF Pmarker</td>
                        <td>Gene type is TF Pmarker.</td>
                        <td><a download="download_TF Pmarker.txt" href="public/download/download_TF%20Pmarker.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                    <tr>
                        <td>TFMarker</td>
                        <td>Gene type is TFMarker.</td>
                        <td><a download="TFMarker.txt" href="public/download/download_TFMarker.csv" target="_blank"><i class="ri-download-2-line"></i></a></td>
                    </tr>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
<?php include "public/footer.php"; ?>
</body>
</html>

