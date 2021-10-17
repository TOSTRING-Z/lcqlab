(function (global) {
    "use strict";
    //默认参数
    var plugin = {
        name: null,
        target: null,
        data: null,
        ipt: null,
        dsearch: null,
        ajax: {
            url: null,
            type: 'POST',
            dataType: 'JSON',
            async: false,
            data: null
        },
        api: {
            change: function (e) {
            }

        }
    };

    function init(plugin) {
        plugin.target = document.querySelector(plugin.target)
        plugin.target.innerHTML = ""
        $("<input>", {
            "type": "text",
            "id": plugin.name + "_ipt",
            "name": plugin.name,
            "style": "'text-transform:uppercase'",
            "class": "form-control"
        }).appendTo(plugin.target);
        $("<div>", {
            "id": plugin.name + "_divsearch"
        }).appendTo(plugin.target);
        plugin.ipt = document.getElementById(plugin.name + "_ipt");
        plugin.dsearch = document.getElementById(plugin.name + "_divsearch");

        var fun = {
            on: function (doc, e, fun) {
                doc.addEventListener ? doc.addEventListener(e, fun, false) : doc.attendEvent("on" + e, fun);
            },
            un: function (doc, e, fun) {
                doc.removeEventListener ? doc.removeEventListener(e, fun, false) : doc.detachEvent("on" + e, fun);
            },
            list: function (e) {
                return function () {
                    var inputhtml = [];
                    var len = plugin.data.length;
                    plugin.dsearch.innerHTML = "";
                    if (e.value != "") {
                        len = 0;
                        for (var i = 0; i < plugin.data.length; i++) {
                            if (plugin.data[i].label.toUpperCase().indexOf(e.value.toUpperCase()) != -1) {
                                len++;
                                inputhtml.push("<a class='inputhtmlA' style='width:" + e.offsetWidth + "px;'>" + plugin.data[i].label + "</a>");
                            }
                        }
                    } else {
                        for (var i = 0; i < plugin.data.length; i++) {
                            inputhtml.push("<a class='inputhtmlA' style='width:" + (e.offsetWidth - 10) + "px;'>" + plugin.data[i].label + "</a>");
                        }
                        len = plugin.data.length;
                    }
                    plugin.dsearch.innerHTML = "<div id='" + plugin.name + "_search" + "' style='width:" + (e.offsetWidth) + "px;" + (len > 4 ? 'overflow-y:scroll;z-index: 10;;height:180px' : 'overflow-y:none') + ";'>"+inputhtml.join("\n")+"</div>";
                }
            },
            mouseleave: function (e) {
                return function () {
                    plugin.api.change();
                    e.innerHTML = "";
                }
            },
            inputJoinValue: function (e1,e2) {
                return function(){
                    var event = event || window.event;
                    e1.value = event.target.innerText
                    e2.innerHTML = ""
                    plugin.api.change();
                }
            }
        };
        fun.on(plugin.ipt, "click", fun.list(plugin.ipt));
        fun.on(plugin.ipt, "input", fun.list(plugin.ipt));
        fun.on(plugin.target, "mouseleave", fun.mouseleave(plugin.dsearch));
        fun.on(plugin.dsearch, "click", fun.inputJoinValue(plugin.ipt,plugin.dsearch));

    }

    function reinput(plu) {
        var plugin_copy = JSON.parse(JSON.stringify(plugin));
        this.plugin_copy = (function (plu) {
            if (!plu) {
                return plugin_copy
            } else {
                Object.keys(plu).forEach((key) => {
                    if (typeof (plu[key]) == "object") {
                        Object.keys(plu[key]).forEach((key1) => {
                            plugin_copy[key][key1] = plu[key][key1];
                        })
                    } else {
                        plugin_copy[key] = plu[key];
                    }
                });
                return plugin_copy
            }
        })(plu);
        if (!this.plugin_copy.data)
            $.ajax({
                url: this.plugin_copy.ajax.url,
                type: this.plugin_copy.ajax.type,
                dataType: this.plugin_copy.ajax.dataType,
                async: this.plugin_copy.ajax.async,
                data: this.plugin_copy.ajax.data,
                success: (data) => {
                    this.plugin_copy.data = data.filter(function(row){return row.label});
                    init(this.plugin_copy);
                }
            });
        this.val = (val,update_list) => {
            let curent_this = this
            curent_this.plugin_copy.ipt.value = val
            curent_this.plugin_copy.ipt.value = val
            let data = update_list.map(function (e) {
                return `"`+e.plugin_copy.name+`":"`+e.plugin_copy.ipt.value+`"`
            })
            update_list.forEach(function (e) {
                if(e.plugin_copy.name !== curent_this.plugin_copy.name)
                    e.updateAjax(JSON.parse(`{`+data.join(',')+`}`))
            })
        };
        this.change = (update_list) => {
            let curent_this = this
            let data = update_list.map(function (e) {
                return `"`+e.plugin_copy.name+`":"`+e.plugin_copy.ipt.value+`"`
            })
            update_list.forEach(function (e) {
                if(e.plugin_copy.name !== curent_this.plugin_copy.name)
                    e.updateAjax(JSON.parse(`{`+data.join(',')+`}`))
            })
        };
        this.reset = (update_list) => {
            update_list.forEach(function (e) {
                e.updateAjax({})
                e.plugin_copy.ipt.value = ""
            })
        };
        this.update = (data) => {
            this.plugin_copy.data = data.filter(function(row){return row.label})
        };
        this.updateAjax = (data) => {
            this.plugin_copy.data = [{"label": 'Loading . . .'}];
            $.ajax({
                url: this.plugin_copy.ajax.url,
                type: this.plugin_copy.ajax.type,
                dataType: this.plugin_copy.ajax.dataType,
                data: data,
                success: (data) => {
                    this.plugin_copy.data = data.filter(function(row){return row.label})
                }
            })
        };
    }

    global.reinput = reinput;
})(this);