function table(id,data,pageRow){
    var tbody = document.getElementById(id).children[1];
    var ul = document.getElementById(id+"_ul");

    var data = data;

    var dataLength = data.length;
    var curPage = 1;
    var pageRow = pageRow;
    var pageAll = parseInt(dataLength/pageRow)<dataLength/pageRow?parseInt(dataLength/pageRow+1):parseInt(dataLength/pageRow);
    function pageFresh() {
        tbody.innerHTML = "";
        ul.innerHTML = "";
        curPage = parseInt(curPage);
        var start = (curPage-1)*pageRow;
        var end = dataLength>curPage*pageRow?curPage*pageRow:dataLength;
        //表格
        for (let i = start;i < end; i ++) {
            var row = data[i];
            var tr = document.createElement("tr");

            Object.keys(row).forEach(function (key) {
                var td = document.createElement("td");
                td.innerHTML = row[key];

                tr.appendChild(td);

            })
            tbody.appendChild(tr);
        }
        //跳转界面后已选择项判断
        var url_param = "",
            params = [],
            arr_params = [];
        var url = decodeURIComponent(window.location.href);
        var url_param = url.split("?")[1];
        if(url_param){
            var params = url_param.split("=")[1];
            var params = params.split("|");
            var arr_params = new Array;
            for(let param in params) {
                $("#"+params[param].split(":")[1].slice(1, -1).replace(/[^0-9A-Za-z]/g,"_")).attr("class","list-group-item active");
                // console.log(params[param].split(":")[1].slice(1, -1).replace(/[^0-9A-Za-z]/g,"_"));
                arr_params[params[param]]=params[param];
            }
        }
        // 页面跳转
        $("a").click(function(doc){
            if(doc.target.dataset.type == "collapse")return;
            if (doc.target.className == "list-group-item active"||doc.target.className == "list-group-item") {
                var type = doc.target.dataset.type;
                var name = doc.target.dataset.name;
                var sub_params = type+":'"+name+"'";
                if(arr_params[sub_params] != sub_params){
                    arr_params[sub_params] = sub_params;
                }
                else{
                    arr_params[sub_params] = "";
                }
                var select = "select=";
                var select_is = 0;
                for(i in arr_params){
                    if(arr_params[i] == "")continue;
                    select_is = 1;
                    select += (arr_params[i]+"|");
                }
                if(select_is == 1){
                    select = select.slice(0, -1);
                }
                else{
                    select = "";
                }
                window.location.href = "browse.php?"+select.replace(/\+/g, '%2B') ;
            }
        });
        //分页按钮
        if (dataLength >5){
            // var li_previous = document.createElement("li");
            // var a_previous = document.createElement("a");
            // a_previous.innerHTML = "Previous";
            // a_previous.id = "Previous";
            // li_previous.appendChild(a_previous);
            // ul.appendChild(li_previous);
            if (pageAll <= 5) {
                for (let i = 1;i <= pageAll;i ++) {
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = i.toString();
                    a.id = id+i.toString();
                    li.appendChild(a);
                    ul.appendChild(li);
                }
            }
            else {
                if (curPage <= 3) {
                    for (let i = 1; i <= 4; i++) {
                        var li = document.createElement("li");
                        var a = document.createElement("a");
                        a.innerHTML = i.toString();
                        a.id = id+i.toString();
                        li.appendChild(a);
                        ul.appendChild(li);
                    }
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = "...";
                    li.appendChild(a);
                    ul.appendChild(li);
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = pageAll;
                    a.id = id+pageAll.toString();
                    li.appendChild(a);
                    ul.appendChild(li);
                }
                else if (curPage >= pageAll-2) {
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = 1;
                    a.id = id+"1";
                    li.appendChild(a);
                    ul.appendChild(li);
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = "...";
                    li.appendChild(a);
                    ul.appendChild(li);
                    for (let i = pageAll-3;i <= pageAll;i ++) {
                        var li = document.createElement("li");
                        var a = document.createElement("a");
                        a.innerHTML = i;
                        a.id = id+i.toString();
                        li.appendChild(a);
                        ul.appendChild(li);
                    }
                }
                else {
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = 1;
                    a.id = id+"1";
                    li.appendChild(a);
                    ul.appendChild(li);
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = "...";
                    li.appendChild(a);
                    ul.appendChild(li);
                    //console.log(curPage);
                    for (let i = curPage-1;i <= curPage+1;i ++) {
                        var li = document.createElement("li");
                        var a = document.createElement("a");
                        a.innerHTML = i;
                        a.id = id+i.toString();
                        li.appendChild(a);
                        ul.appendChild(li);
                    }
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = "...";
                    li.appendChild(a);
                    ul.appendChild(li);
                    var li = document.createElement("li");
                    var a = document.createElement("a");
                    a.innerHTML = pageAll;
                    a.id = id+pageAll.toString();
                    li.appendChild(a);
                    ul.appendChild(li);
                }
            }
            // var li_next = document.createElement("li");
            // var a_next = document.createElement("a");
            // a_next.innerHTML = "Next";
            // a_next.id = "Next";
            // li_next.appendChild(a_next);
            // ul.appendChild(li_next);
            document.getElementById(id+curPage.toString()).parentElement.className = "active";
            ul.onclick = function (e) {
                if (e.target.nodeName == "A"&&e.target.innerHTML !="...") {
                    switch (e.target.innerHTML) {
                        case 'Previous':
                            curPage = curPage>1?curPage-1:1;
                            break;
                        case 'Next':
                            curPage = curPage==pageAll?curPage:curPage+1;
                            break;
                        default:
                            curPage = e.target.innerHTML;
                            break;
                    }
                    pageFresh();
                }
            }
        }
    }
    pageFresh();
}

function select_data(doc) {
    //跳转界面后已选择项判断
    var url_param = "",
        params = [],
        arr_params = [];
    var url = decodeURIComponent(window.location.href);
    var url_param = url.split("?")[1];
    if(url_param){
        var params = url_param.split("=")[1];
        var params = params.split("|");
        var arr_params = new Array;
        //console.log(params);
        for(let param in params) {
            $("#"+params[param].split(":")[1].slice(1, -1).replace(/[^0-9A-Za-z]/g,"_")).attr("class","list-group-item active");
            arr_params[params[param]]=params[param];
        }
    }
    // 页面跳转
    // console.log(doc.className);
    var type = doc.dataset.type;
    var name = doc.dataset.name;
    var sub_params = type+":'"+name+"'";
    if(arr_params[sub_params] != sub_params){
        arr_params[sub_params] = sub_params;
    }
    else{
        arr_params[sub_params] = "";
    }
    var select = "select=";
    var select_is = 0;
    for(i in arr_params){
        if(arr_params[i] == "")continue;
        select_is = 1;
        select += (arr_params[i]+"|");
    }
    if(select_is == 1){
        select = select.slice(0, -1);
    }
    else{
        select = "";
    }
    window.location.href = "browse.php?"+select.replace(/\+/g, '%2B') ;
}