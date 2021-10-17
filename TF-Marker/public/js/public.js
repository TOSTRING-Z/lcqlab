function changeFrameHeight(e){
    try{
        let bHeight = e.contentWindow.document.body.scrollHeight;
        let dHeight = e.contentWindow.document.documentElement.scrollHeight;
        let height = Math.max(bHeight, dHeight);
        e.height = height+50;
    }catch (ex){}
}

window.onresize = function(){
    changeFrameHeight();
};

function infoMore (e) {
    switch (e.className) {
        case "ri-add-circle-line":
            e.parentElement.style.overflow = "visible";
            e.parentElement.style.whiteSpace = "normal";
            e.className = "ri-indeterminate-circle-line";
            break
        case "ri-indeterminate-circle-line":
            e.parentElement.style.overflow = "hidden";
            e.parentElement.style.whiteSpace = "nowrap";
            e.className = "ri-add-circle-line";
            break

    }
}

function crc_detail(sample_id, strategy, tf_name, interacting_gene) {
    $('#crc_detail_modal').modal('show');
    $("#crc_detail_modal_body").html("");
    $.ajax({
        url: "crc_sample_server.php",
        data: {
            sample_id: sample_id,
            tf_name: tf_name,
            interacting_gene: interacting_gene,
            strategy: strategy
        },
        dataType: "HTML",
        success: function (html) {
            $("#crc_detail_modal_body").html(html);
        }
    })
}
function doPost(to, p) {  // to:提交动作（action）,p:参数
    var myForm = document.createElement("form");
    myForm.method = "post";
    myForm.action = to;
    for (var i in p){
        var myInput = document.createElement("input");
        myInput.setAttribute("name", i);  // 为input对象设置name
        myInput.setAttribute("value", p[i]);  // 为input对象设置value
        myForm.appendChild(myInput);
    }
    document.body.appendChild(myForm);
    myForm.submit();
    document.body.removeChild(myForm);  // 提交后移除创建的form
}
function getQueryString(url,name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = url.search.substr(1).match(reg);
    if (r != null)
        return unescape(r[2]);
    return null;
}
var itemStyle = {
    normal: {
        //每根柱子颜色设置
        color: function (params) {
            let colorList = [
                "#e35652", "#6cb8ea", "#c2e277", "#d48265", "#91c7ae",
                "#becc5a", "#ca8622", "#bda29a", "#3b805b", "#546570",
                "#e6c25a", "#81e9c0", "#bda29a", "#775fed", "#546570",
                "#c4ccd3", "#4BABDE", "#FFDE76", "#E43C59", "#37A2DA",
                "#d45897", "#639ec6", "#61a0a8", "#d48265", "#91c7ae",
                "#de5875", "#ca8622", "#f08261", "#9b76e9", "#546570",
                "#dc4f4b", "#65ce6f", "#96df6e", "#d48265", "#91c7ae",
            ];
            return colorList[params.dataIndex % (colorList.length - 1)];
        }
    }
};