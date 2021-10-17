<?php include(__DIR__ . "/../public/public.php") ?>
<div class="modal fade" id="crc_detail_modal" tabindex="-1" role="dialog" aria-labelledby="crc_detail_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="crc_detail_modal_label">CRC detail</h4>
            </div>
            <div class="modal-body" id="crc_detail_modal_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<footer>
    <div>
        <div class="container-fluid">
            <div class="col-lg-2 col-xs-3 col-xs-offset-1 col-lg-offset-1">
                <a href="/<?php echo $web_title ?>/index.php">Home</a><br>
                <a href="/<?php echo $web_title ?>/browse.php">Browse</a><br>
                <a href="/<?php echo $web_title ?>/search.php">Search</a><br>
            </div>
            <div class="col-lg-2 col-xs-3">
                <a href="/<?php echo $web_title ?>/download.php">Download</a><br>
                <a href="/<?php echo $web_title ?>/statistics.php">Statistics</a><br>
                <a href="/<?php echo $web_title ?>/submit.php">Submit</a>
            </div>
            <div class="col-lg-2 col-xs-3">
                <a href="/<?php echo $web_title ?>/contact.php">Contact</a><br>
                <a href="/<?php echo $web_title ?>/help.php">Help</a>
            </div>
            <div class="col-lg-3 col-xs-7 col-xs-push-1">
                <b>Copyright &copy; HMU</b><br>
                <a href="http://www.beian.miit.gov.cn">黑ICP备16009434号-1</a><br>
                <a href="http://www.licpathway.net/" target="_blank" class="bottom-brand">Li C Lab</a>
            </div>
            <div class="col-lg-2 col-xs-4">
                <div class="row"><a class="pull-right top" href="#top">BACK TO TOP</a></div>
            </div>
        </div>
    </div>
</footer>
<script>
    $(document).ready(() => {
        setInterval(() => {var sp1 = location.toString().split('/');
            sp1.reverse();
            var sp2 = sp1.length === 5 ? sp1[0]:(sp1[1]+".php");
            if(sp2 === '')
                sp2 = 'index.php';
            sp2 = sp2.split(/[#?]/i)[0]
            $('a[href*="/'+sp2+'"]').parents('li').addClass('active');

            $("#loading").css("visibility", "hidden");
            setTimeout(function () {
                $("*[height-to]").each(function (t,e) {
                    var height_to = $(e).attr("height-to");
                    if(height_to){
                        $(e).css("height",$("#"+height_to).css("height"));
                        $(e).css("overflow-y","auto");
                        $(e).css("overflow-x","hidden");
                    }
                })
            },300);
            $("td").each(function(i,e){
                if(e.innerHTML==""){
                    $(e).text("/")
                }
            })},1000,10)
    });
</script>