(function (d) {
    var e = { data: undefined, search: true, title: "SelectMenu", regular: false, rightClick: false, arrow: false, position: "left", embed: false, lang: "cn", multiple: false, listSize: 10, maxSelectLimit: 0, selectToCloseList: false, initSelected: undefined, keyField: "id", showField: "name", searchField: undefined, andOr: "AND", orderBy: undefined, pageSize: 100, formatItem: undefined, eSelect: undefined };
    var b = function (f, g) {
        this.target = f;
        this.setOption(g);
        if (this.option.embed && !d(f).is("div")) {
            console.warn("SelectMenu embed mode need a div container element!");
            return;
        }
        this.setLanguage();
        this.setCssClass();
        this.setProp();
        if (g.regular) {
            this.setRegularMenu();
        }
        else {
            this.setElem();
        }
        if (!g.rightClick) {
            this.populate();
        }
        this.eInput();
        if (!g.embed) {
            this.eWhole();
        }
        this.atLast();
    };
    b.version = "1.0";
    b.dataKey = "selectMenuObject";
    b.objStatusKey = "selectMenu-self-mark";
    b.objStatusIndex = "selectMenu-self-index";
    b.dataTypeList = "SelectMenuList";
    b.dataTypeGroup = "SelectMenuGroup";
    b.dataTypeMenu = "SelectMenuMenu";
    b.prototype.setOption = function (h) {
        h.searchField = (h.searchField === undefined) ? h.showField : h.searchField;
        if (h.regular && h.title === e.title) {
            h.title = false;
        }
        if (h.embed || h.richCombo) {
            h.arrow = false;
        }
        h.andOr = h.andOr.toUpperCase();
        if (h.andOr !== "AND" && h.andOr !== "OR") {
            h.andOr = "AND";
        }
        var f = ["searchField"];
        for (var g = 0; g < f.length; g++) {
            h[f[g]] = this.strToArray(h[f[g]]);
        }
        h.orderBy = (h.orderBy === undefined) ? h.searchField : h.orderBy;
        h.orderBy = this.setOrderbyOption(h.orderBy, h.showField);
        if (d.type(h.data) === "string") {
            h.autoSelectFirst = false;
        }
        if (d.type(h.listSize) !== "number" || h.listSize < 0) {
            h.listSize = 12;
        }
        this.option = h;
    };
    b.prototype.strToArray = function (f) {
        if (!f) {
            return "";
        }
        return f.replace(/[\s???]+/g, "").split(",");
    };
    b.prototype.setOrderbyOption = function (g, k) {
        var f = [], j = [];
        if (typeof g == "object") {
            for (var h = 0; h < g.length; h++) {
                j = d.trim(g[h]).split(" ");
                f[h] = (j.length == 2) ? j : [j[0], "ASC"];
            }
        }
        else {
            j = d.trim(g).split(" ");
            f[0] = (j.length == 2) ? j : (j[0].match(/^(ASC|DESC)$/i)) ? [k, j[0]] : [j[0], "ASC"];
        }
        return f;
    };
    b.prototype.setLanguage = function () {
        var f;
        switch (this.option.lang) {
            case "cn":
                f = { select_all_btn: "???????????? (???????????????) ??????", remove_all_btn: "???????????????????????????", close_btn: "???????????? (Esc???)", loading: "?????????...", select_ng: "?????????????????????????????????.", select_ok: "OK : ????????????.", not_found: "???????????????", ajax_error: "????????????????????????????????????" };
                break;
            case "en":
                f = { select_all_btn: "Select All (Tabs) items", remove_all_btn: "Clear all selected items", close_btn: "Close Menu (Esc key)", loading: "loading...", select_ng: "Attention : Please choose from among the list.", select_ok: "OK : Correctly selected.", not_found: "not found", ajax_error: "An error occurred while connecting to server." };
                break;
            case "ja":
                f = { select_all_btn: "???????????? ?????????????????????????????? ???????????????", remove_all_btn: "????????????????????????????????????????????????", close_btn: "????????? (Tab??????)", loading: "???????????????...", select_ng: "?????? : ?????????????????????????????????????????????", select_ok: "OK : ?????????????????????????????????", not_found: "(0 ???)", ajax_error: "?????????????????????????????????????????????????????????" };
                break;
            case "de":
                f = { select_all_btn: "W??hlen Sie alle (oder aktuellen Registerkarten) aus", remove_all_btn: "Alle ausgew??hlten Elemente l??schen", close_btn: "Schlie??en (Tab)", loading: "lade...", select_ng: "Achtung: Bitte w??hlen Sie aus der Liste aus.", select_ok: "OK : Richtig ausgew??hlt.", not_found: "nicht gefunden", ajax_error: "Bei der Verbindung zum Server ist ein Fehler aufgetreten." };
                break;
            case "es":
                f = { select_all_btn: "Seleccionar todos los elementos (o la pesta??a actual)", remove_all_btn: "Borrar todos los elementos seleccionados", close_btn: "Cerrar (tecla TAB)", loading: "Cargando...", select_ng: "Atencion: Elija una opcion de la lista.", select_ok: "OK: Correctamente seleccionado.", not_found: "no encuentre", ajax_error: "Un error ocurri?? mientras conectando al servidor." };
                break;
            case "pt-br":
                f = { select_all_btn: "Selecione todos os itens (ou guia atual)", remove_all_btn: "Limpe todos os itens selecionados", close_btn: "Fechar (tecla TAB)", loading: "Carregando...", select_ng: "Aten????o: Escolha uma op????o da lista.", select_ok: "OK: Selecionado Corretamente.", not_found: "n??o encontrado", ajax_error: "Um erro aconteceu enquanto conectando a servidor." };
                break;
        }
        this.message = f;
    };
    b.prototype.setCssClass = function () {
        var f = { target_clicked: "sm_target_clicked", container: "sm_container", container_open: "sm_container_open", container_embed: "sm_embed", header: "sm_header", re_area: "sm_result_area", re_tabs: "sm_result_tabs", re_list: "sm_list_mode", control_box: "sm_control_box", two_btn: "sm_two_btn", element_box: "sm_element_box", results: "sm_results", re_off: "sm_results_off", select: "sm_over", selected_icon: "sm_selected_icon", item_text: "sm_item_text", select_ok: "sm_select_ok", select_ng: "sm_select_ng", selected: "sm_selected", input_off: "sm_input_off", message_box: "sm_message_box", btn_close: "sm_close_button", btn_selectall: "sm_selectall_button", btn_removeall: "sm_removeall_button", btn_on: "sm_btn_on", btn_out: "sm_btn_out", input: "sm_input", input_area: "sm_input_area", clear_btn: "sm_clear_btn", menu_divider: "sm_divider", menu_regular: "sm_regular", menu_arrow: "sm_arrow", menu_arraw_have_title: "sm_have_title", menu_disabled: "sm_disabled", menu_header: "sm_header", direction_top: "sm_arrow_top", direction_bottom: "sm_arrow_bottom" };
        this.css_class = f;
    };
    b.prototype.setProp = function () {
        this.prop = { values: [], data: undefined, data_index: 0, current_page: 1, max_page: 1, key_select: false, prev_value: "", selected_text: "", last_input_time: undefined, data_type: b.dataTypeList, menu_tab_id_prefix: "selectmenu_tab_", x: undefined, y: undefined };
    };
    b.prototype.checkDataType = function (i) {
        var f = this, g = this.option;
        if (i && d.isArray(i) && i.length) {
            if (g.regular) {
                return b.dataTypeMenu;
            }
            else {
                var h = i[0];
                if (h.hasOwnProperty("title") && h.hasOwnProperty("list") && d.isArray(h.list)) {
                    return b.dataTypeGroup;
                }
                else {
                    return b.dataTypeList;
                }
            }
        }
        else {
            return null;
        }
    };
    b.prototype.setElem = function () {
        var f = this, h = this.option;
        var g = {};
        g.container = h.embed ? d(f.target).addClass(this.css_class.container_embed) : d("<div>");
        d(g.container).addClass(this.css_class.container).addClass(this.css_class.direction_bottom);
        if (h.title) {
            g.header = d("<div>").addClass(this.css_class.header);
            d(g.header).append("<h3>" + h.title + "</h3>");
            if (h.multiple) {
                g.selectAllButton = d('<button type="button"><i class="iconfont icon-selectall"></i></button>').attr("title", this.message.select_all_btn).addClass(this.css_class.btn_selectall);
                g.removeAllButton = d('<button type="button"><i class="iconfont icon-removeall"></i></button>').attr("title", this.message.remove_all_btn).addClass(this.css_class.btn_removeall);
                d(g.header).append(g.selectAllButton);
                d(g.header).append(g.removeAllButton);
            }
            if (!h.embed) {
                g.closeButton = d('<button type="button">??</button>').attr("title", f.message.close_btn).addClass(this.css_class.btn_close);
                d(g.header).append(g.closeButton);
            }
        }
        g.inputArea = d("<div>").addClass(this.css_class.input_area);
        g.input = d('<input type="text" autocomplete="off">').addClass(this.css_class.input);
        g.resultArea = d("<div>").addClass(this.css_class.re_area);
        g.resultTabs = d("<div>").addClass(this.css_class.re_tabs);
        g.results = d("<ul>").addClass(this.css_class.results);
        g.selectedIcon = d('<i class="iconfont icon-selected">');
        if (h.arrow) {
            g.arrow = d("<div>").addClass(this.css_class.menu_arrow);
            if (h.title) {
                d(g.arrow).addClass(this.css_class.menu_arraw_have_title);
            }
            d(g.container).append(g.arrow);
        }
        if (h.title) {
            d(g.container).append(g.header);
        }
        if (h.search) {
            d(g.container).append(g.inputArea);
            d(g.inputArea).append(g.input);
        }
        d(g.container).append(g.resultTabs);
        d(g.container).append(g.resultArea);
        d(g.resultArea).append(g.results);
        if (!h.embed) {
            d(document.body).append(g.container);
        }
        this.elem = g;
    };
    b.prototype.setRegularMenu = function () {
        var h = this.option, f = this;
        var g = {};
        g.container = h.embed ? d(f.target).addClass(this.css_class.container_embed) : d("<div>");
        d(g.container).addClass(this.css_class.container).addClass(this.css_class.direction_bottom).addClass(this.css_class.menu_regular);
        if (h.title) {
            g.header = d("<div>").addClass(this.css_class.header);
            d(g.header).append("<h3>" + h.title + "</h3>");
            if (!h.embed) {
                g.closeButton = d('<button type="button">??</button>').attr("title", f.message.close_btn).addClass(this.css_class.btn_close);
            }
        }
        g.resultArea = d("<div>").addClass(this.css_class.re_area);
        g.results = d("<ul>").addClass(this.css_class.results);
        if (h.arrow) {
            g.arrow = d("<div>").addClass(this.css_class.menu_arrow);
            if (h.title) {
                d(g.arrow).addClass(this.css_class.menu_arraw_have_title);
            }
            d(g.container).append(g.arrow);
        }
        if (h.title) {
            d(g.container).append(g.header);
            if (!h.embed) {
                d(g.header).append(g.closeButton);
            }
        }
        d(g.container).append(g.resultArea);
        d(g.resultArea).append(g.results);
        if (!h.embed) {
            d(document.body).append(g.container);
        }
        this.elem = g;
    };
    b.prototype.regularMenuInit = function () {
        var h = this.prop.data, g = this.option, f = this;
        if (h && d.isArray(h) && h.length > 0) {
            d(f.elem.results).empty().hide();
            d.each(h, function (l, m) {
                var j = d("<li>");
                if (m.content === "sm_divider") {
                    d(j).addClass(f.css_class.menu_divider);
                }
                else {
                    if (m.header) {
                        d(j).html(d('<a href="javascript:void(0);">').html(m.content)).addClass(f.css_class.menu_header);
                    }
                    else {
                        if (m.url) {
                            var k = d("<a>").html(m.content);
                            if (m.disabled) {
                                d(k).attr("href", "javascript:void(0);");
                            }
                            else {
                                d(k).attr("href", m.url);
                            }
                            d(j).html(k);
                        }
                        else {
                            if (m.callback && d.isFunction(m.callback)) {
                                var k = d('<a href="javascript:void(0);">').html(m.content).on("click.selectMenu", function (i) {
                                    i.stopPropagation();
                                    if (m.disabled) {
                                        return;
                                    }
                                    m.callback();
                                    f.hideResults(f);
                                });
                                d(j).html(k);
                            }
                        }
                        if (m.disabled) {
                            d(j).addClass(f.css_class.menu_disabled);
                        }
                    }
                }
                d(f.elem.results).append(j);
            });
            d(f.elem.results).show();
            if (!g.embed) {
                this.calcResultsSize(this);
                d(f.elem.container).addClass(f.css_class.container_open);
            }
        }
    };
    b.prototype.showMenu = function (f) {
        f.populate();
        if (d(f.target).is("button")) {
            d(f.target).addClass(f.css_class.target_clicked);
        }
    };
    b.prototype.setInitSelected = function (g, h) {
        var i = g.option;
        if (d.type(i.initSelected) !== "undefined" && !i.regular && h && d.isArray(h) && h.length > 0) {
            var j = String(i.initSelected);
            var f = j.split(",");
            d.each(h, function (k, l) {
                var m = String(l[i.keyField]);
                if (m && d.inArray(m, f) !== -1) {
                    g.prop.values.push(l);
                }
            });
            i.initSelected = undefined;
        }
    };
    b.prototype.eInput = function () {
        var f = this, h = this.option, g = f.elem;
        if (!h.regular && h.search) {
            d(g.input).keyup(function (i) {
                f.processKey(f, i);
            }).keydown(function (i) {
                f.processControl(f, i);
            });
        }
        if (h.title) {
            d(g.closeButton).click(function (i) {
                f.hideResults(f);
            });
            if (!h.regular) {
                d(g.header).not("button").click(function (i) {
                    d(g.input).focus();
                });
                d(g.inputArea).not(g.input).click(function (i) {
                    d(g.input).focus();
                });
                if (h.multiple) {
                    d(g.selectAllButton).click(function (i) {
                        i.stopPropagation();
                        f.selectAllLine(f);
                    });
                    d(g.removeAllButton).click(function (i) {
                        i.stopPropagation();
                        f.clearAll(f);
                    });
                }
            }
        }
        if (!h.regular && f.prop.data_type === b.dataTypeGroup) {
            d(g.resultTabs).on("click.selectMenu", "a", function (j) {
                j.stopPropagation();
                if (!d(this).hasClass("active")) {
                    var i = d(this).closest("li");
                    d(i).siblings().children("a").removeClass("active");
                    d(this).addClass("active");
                    f.prop.data_index = parseInt(d(this).attr("data_index"));
                    f.populate();
                }
            });
        }
        if (h.rightClick) {
            d(f.target).on("contextmenu", function (j) {
                j.preventDefault();
                j.stopPropagation();
                j.cancelBubble = true;
                j.returnValue = false;
                var k = document.documentElement.scrollLeft || document.body.scrollLeft;
                var i = document.documentElement.scrollTop || document.body.scrollTop;
                f.prop.x = j.pageX || j.clientX + k;
                f.prop.y = j.pageY || j.clientY + i;
                if (!f.isVisible(f)) {
                    f.populate();
                }
                else {
                    f.calcResultsSize(f);
                }
                return false;
            }).mouseup(function (i) {
                if (i.button != 2) {
                    f.hideResults(f);
                }
            });
            f.hideResults(f);
        }
    };
    b.prototype.eWhole = function () {
        var f = this;
        d(document).off("mouseup.selectMenu").on("mouseup.selectMenu", function (h) {
            var g = h.target || h.srcElement;
            var i = d(g).closest("div." + f.css_class.container);
            d("div." + f.css_class.container + "." + f.css_class.container_open).each(function () {
                var j = d(this).data(b.dataKey);
                if (this == i[0] || j.target == g || d(g).closest(j.target).size() > 0) {
                    return;
                }
                j.hideResults(j);
            });
        });
    };
    b.prototype.eResultList = function () {
        var f = this;
        d(f.elem.results).children("li").mouseenter(function () {
            if (f.prop.key_select) {
                f.prop.key_select = false;
                return;
            }
            if (!d(this).hasClass("sm_message_box")) {
                d(this).addClass(f.css_class.select);
            }
        }).mouseleave(function () {
            d(this).removeClass(f.css_class.select);
        }).click(function (g) {
            if (f.prop.key_select) {
                f.prop.key_select = false;
                return;
            }
            g.preventDefault();
            g.stopPropagation();
            f.selectCurrentLine(f, false);
        });
    };
    b.prototype.atLast = function () {
        var f = this, g = this.option;
        if (g.search && !g.embed && !g.rightClick) {
            d(f.elem.input).focus();
        }
        d(f.elem.container).data(b.dataKey, f);
        if (d(f.target).is("button,.btn") && !g.embed && !g.rightClick) {
            d(f.target).addClass(f.css_class.target_clicked);
        }
    };
    b.prototype.ajaxErrorNotify = function (f, g) {
        f.showMessage(f.message.ajax_error);
    };
    b.prototype.showMessage = function (f, h) {
        if (!h) {
            return;
        }
        var g = '<li class="sm_message_box"><i class="iconfont icon-warn"></i> ' + h + "</li>";
        d(f.elem.results).empty().append(g);
        f.calcResultsSize(f);
        d(f.elem.container).addClass(f.css_class.container_open);
        d(f.elem.control).hide();
    };
    b.prototype.checkValue = function (g) {
        var f = d(g.elem.input).val();
        if (f != g.prop.prev_value) {
            g.prop.prev_value = f;
            g.suggest(g);
        }
    };
    b.prototype.processKey = function (f, g) {
        if (d.inArray(g.keyCode, [38, 40, 27, 9, 13]) === -1) {
            if (d.type(f.option.data) === "string") {
                f.prop.last_input_time = g.timeStamp;
                setTimeout(function () {
                    if ((g.timeStamp - f.prop.last_input_time) === 0) {
                        f.checkValue(f);
                    }
                }, f.option.inputDelay * 1000);
            }
            else {
                f.checkValue(f);
            }
        }
    };
    b.prototype.processControl = function (f, g) {
        if ((d.inArray(g.keyCode, [38, 40, 27, 9]) > -1 && d(f.elem.container).is(":visible")) || (d.inArray(g.keyCode, [13, 9]) > -1 && f.getCurrentLine(f))) {
            g.preventDefault();
            g.stopPropagation();
            g.cancelBubble = true;
            g.returnValue = false;
            switch (g.keyCode) {
                case 38:
                    f.prop.key_select = true;
                    f.prevLine(f);
                    break;
                case 40:
                    if (d(f.elem.results).children("li").length) {
                        f.prop.key_select = true;
                        f.nextLine(f);
                    }
                    else {
                        f.suggest(f);
                    }
                    break;
                case 9:
                    f.selectCurrentLine(f, true);
                    break;
                case 13:
                    f.selectCurrentLine(f, true);
                    break;
                case 27:
                    f.hideResults(f);
                    break;
            }
        }
    };
    b.prototype.populate = function () {
        var f = this, g = this.option;
        if (!g.regular) {
            d(f.elem.input).val("");
        }
        if (g.data) {
            if (d.type(g.data) === "array") {
                f.prop.data = g.data;
            }
            else {
                if (d.type(g.data) === "function") {
                    f.prop.data = g.data();
                }
            }
        }
        if (d.type(f.prop.data) === "array") {
            this.prop.data_type = this.checkDataType(f.prop.data);
        }
        if (d.type(g.data) !== "string") {
            f.setInitSelected(f, f.prop.data);
        }
        if (g.regular) {
            f.regularMenuInit();
        }
        else {
            f.suggest(f);
        }
    };
    b.prototype.suggest = function (f) {
        var g, h = f.option;
        var i = d.trim(d(f.elem.input).val());
        if (h.multiple) {
            g = i;
        }
        else {
            if (i && i === f.prop.selected_text) {
                g = "";
            }
            else {
                g = i;
            }
        }
        g = g.split(/[\s???]+/);
        f.setLoading(f);
        if (d.type(h.data) === "array" || d.type(h.data) === "function") {
            f.searchForJson(f, g);
        }
    };
    b.prototype.setLoading = function (f) {
        if (d(f.elem.results).html() === "") {
            if (!f.option.embed) {
                d(f.elem.container).addClass(f.css_class.container_open);
            }
        }
    };
    b.prototype.searchForJson = function (r, k) {
        var s = r.option, u = r.prop.data;
        var n = [], h = [], f = [], A = {}, w = 0, q = [];
        do {
            h[w] = k[w].replace(/\W/g, "\\$&").toString();
            q[w] = new RegExp(h[w], "gi");
            w++;
        } while (w < k.length);
        var C = [];
        if (r.prop.data_index > (s.data.length - 1) || r.prop.data_index < 0) {
            r.prop.data_index = 0;
        }
        if (r.prop.data_type === b.dataTypeGroup) {
            C = u[r.prop.data_index].list;
        }
        else {
            C = u;
        }
        for (w = 0; w < C.length; w++) {
            var v = false;
            var l = C[w];
            for (var t = 0; t < q.length; t++) {
                var m = l[s.showField];
                if (s.formatItem && d.isFunction(s.formatItem)) {
                    m = s.formatItem(l);
                }
                if (m.match(q[t])) {
                    v = true;
                    if (s.andOr == "OR") {
                        break;
                    }
                }
                else {
                    v = false;
                    if (s.andOr == "AND") {
                        break;
                    }
                }
            }
            if (v) {
                n.push(l);
            }
        }
        var D = new RegExp("^" + h[0] + "$", "gi");
        var B = new RegExp("^" + h[0], "gi");
        var z = [];
        var y = [];
        var x = [];
        for (w = 0; w < n.length; w++) {
            var o = s.orderBy[0][0];
            var g = String(n[w][o]);
            if (g.match(D)) {
                z.push(n[w]);
            }
            else {
                if (g.match(B)) {
                    y.push(n[w]);
                }
                else {
                    x.push(n[w]);
                }
            }
        }
        if (s.orderBy[0][1].match(/^asc$/i)) {
            z = r.sortAsc(r, z);
            y = r.sortAsc(r, y);
            x = r.sortAsc(r, x);
        }
        else {
            z = r.sortDesc(r, z);
            y = r.sortDesc(r, y);
            x = r.sortDesc(r, x);
        }
        f = f.concat(z).concat(y).concat(x);
        A.originalResult = [];
        if (A.keyField === undefined) {
            A.keyField = [];
        }
        if (A.candidate === undefined) {
            A.candidate = [];
        }
        d.each(f, function (j, p) {
            if (p === undefined || d.type(p) !== "object") {
                return true;
            }
            A.originalResult.push(p);
            if (p.hasOwnProperty(s.keyField) && p.hasOwnProperty(s.showField)) {
                A.keyField.push(p[s.keyField]);
                A.candidate.push(p[s.showField]);
            }
        });
        r.prepareResults(r, A, k);
    };
    b.prototype.sortAsc = function (g, f) {
        f.sort(function (j, h) {
            var k = j[g.option.orderBy[0][0]];
            var i = h[g.option.orderBy[0][0]];
            return d.type(k) === "number" ? k - i : String(k).localeCompare(String(i));
        });
        return f;
    };
    b.prototype.sortDesc = function (g, f) {
        f.sort(function (j, h) {
            var k = j[g.option.orderBy[0][0]];
            var i = h[g.option.orderBy[0][0]];
            return d.type(k) === "number" ? i - k : String(i).localeCompare(String(k));
        });
        return f;
    };
    b.prototype.notFoundSearch = function (f) {
        d(f.elem.results).empty();
        f.calcResultsSize(f);
        d(f.elem.container).addClass(f.css_class.container_open);
        f.setCssFocusedInput(f);
    };
    b.prototype.prepareResults = function (g, h, i) {
        if (!h.keyField) {
            h.keyField = false;
        }
        if (g.option.selectOnly && h.candidate.length === 1 && h.candidate[0] == i[0]) {
            d(g.elem.hidden).val(h.keyField[0]);
            this.setButtonAttrDefault();
        }
        var f = false;
        if (i && i.length > 0 && i[0]) {
            f = true;
        }
        g.setInitSelected(g, h.originalResult);
        g.displayResults(g, h, f);
    };
    b.prototype.displayResults = function (w, v, l) {
        var g = w.option, h = w.elem;
        d(h.results).hide().empty();
        if (w.prop.data_type === b.dataTypeGroup) {
            var n = d("<ul>");
            d.each(w.prop.data, function (z, A) {
                var y = d('<a href="javascript:void(0);">').html(A.title).attr({ "tab_id": w.prop.menu_tab_id_prefix + (z + 1), "data_index": z });
                if (z === w.prop.data_index) {
                    d(y).addClass("active");
                }
                var p = d("<li>").append(y);
                n.append(p);
            });
            h.resultTabs.empty().append(n);
        }
        else {
            d(h.resultTabs).hide();
            if (g.title || g.search) {
                h.resultArea.addClass(this.css_class.re_list);
            }
        }
        if (g.multiple && d.type(g.maxSelectLimit) === "number" && g.maxSelectLimit > 0) {
            var j = d("li.selected_tag", h.element_box).size();
            if (j > 0 && j >= g.maxSelectLimit) {
                w.showMessage(w, "?????????????????? " + g.maxSelectLimit + " ?????????");
                return;
            }
        }
        if (v.candidate.length > 0) {
            var t = v.candidate;
            var r = v.keyField;
            for (var k = 0; k < t.length; k++) {
                var q = "", f = false, x = v.originalResult[k];
                if (g.formatItem && d.isFunction(g.formatItem)) {
                    try {
                        q = g.formatItem(x);
                        f = true;
                    }
                    catch (m) {
                        console.error("formatItem?????????????????????????????????????????????");
                        q = t[k];
                    }
                }
                else {
                    q = t[k];
                }
                var o = d("<div>").html('<i class="iconfont icon-selected">').addClass(w.css_class.selected_icon);
                var u = d("<div>").html(q).addClass(w.css_class.item_text);
                var s = d("<li>").append(o).append(u).attr("pkey", r[k]);
                if (!f) {
                    d(s).attr("title", q);
                }
                if (d.inArray(x, w.prop.values) !== -1) {
                    d(s).addClass(w.css_class.selected);
                }
                d(s).data("dataObj", x);
                d(h.results).append(s);
            }
        }
        else {
            var s = '<li class="sm_message_box"><i class="iconfont icon-warn"></i> ' + w.message.not_found + "</li>";
            d(h.results).append(s);
        }
        d(h.results).show();
        w.calcResultsSize(w);
        if (!g.embed) {
            d(h.container).addClass(w.css_class.container_open);
        }
        w.eResultList();
        w.atLast();
    };
    b.prototype.calcResultsSize = function (i) {
        var l = i.option, j = i.elem;
        var k = function () {
            return d(document).height() > d(window).height();
        };
        var f = function () {
            if (!l.regular) {
                var n = d("li:first", j.results).outerHeight();
                var m = n * l.listSize;
                d(j.results).css({ "max-height": m });
            }
        };
        var h = k();
        var g = function () {
            if (l.rightClick) {
                return { top: i.prop.y, left: i.prop.x };
            }
            var q = d(i.target).offset();
            var o = q.top;
            var p = d(j.container).outerWidth();
            var n = Math.round(d(i.target)[0].getBoundingClientRect().width);
            o += d(i.target).outerHeight(true) + 5;
            if (l.arrow && !l.embed) {
                o += d(j.arrow).outerHeight(true);
            }
            var m = q.left;
            switch (l.position) {
                case "left":
                    if (l.arrow) {
                        d(j.arrow).css("left", n / 2);
                    }
                    break;
                case "right":
                    m = m + n - p;
                    if (l.arrow) {
                        d(j.arrow).css("left", p - (n / 2));
                    }
                    break;
                case "center":
                    m = m + (n / 2) - (p / 2);
                    break;
            }
            return { top: o, left: m };
        };
        if (d(j.container).is(":visible")) {
            f();
            if (!l.embed) {
                d(j.container).offset(g());
            }
        }
        else {
            d(j.container).show(1, function () {
                f();
                d(this).offset(g());
            });
        }
        if (h !== k()) {
            d(j.container).offset(g());
        }
    };
    b.prototype.hideResults = function (f) {
        if (f.option.autoFillResult) {
        }
        if (!f.option.regular) {
            d(f.elem.results).empty();
        }
        if (!f.option.embed) {
            d(f.elem.container).removeClass(f.css_class.container_open).hide();
            if (d(f.target).is("button,.btn")) {
                d(f.target).removeClass(f.css_class.target_clicked);
            }
        }
    };
    b.prototype.afterAction = function (f) {
        if (f.option.multiple) {
            if (f.option.selectToCloseList) {
                f.hideResults(f);
                d(f.elem.input).blur();
            }
            else {
                d(f.elem.input).focus();
            }
        }
        else {
            f.hideResults(f);
            d(f.elem.input).blur();
        }
    };
    b.prototype.getCurrentLine = function (f) {
        if (d(f.elem.container).is(":hidden")) {
            return false;
        }
        var g = d("li." + f.css_class.select, f.elem.results);
        if (d(g).size()) {
            return g;
        }
        else {
            return false;
        }
    };
    b.prototype.getSelectedLine = function (f) {
        if (d(f.elem.container).is(":hidden")) {
            return false;
        }
        var g = d("li." + f.css_class.selected, f.elem.results);
        if (d(g).size()) {
            return g;
        }
        else {
            return false;
        }
    };
    b.prototype.selectCurrentLine = function (f, g) {
        var j = f.getCurrentLine(f), i = f.option;
        if (j) {
            var h = d(j).data("dataObj");
            var k = String(h[i.keyField]);
            if (d.inArray(h, f.prop.values) === -1) {
                if (!i.multiple) {
                    f.prop.values.splice(0, f.prop.values.length);
                }
                f.prop.values.push(h);
                d(j).addClass(f.css_class.selected);
            }
            else {
                f.prop.values.splice(d.inArray(h, f.prop.values), 1);
                d(j).removeClass(f.css_class.selected);
            }
            if (i.eSelect && d.isFunction(i.eSelect)) {
                if (i.multiple) {
                    i.eSelect(f.prop.values);
                }
                else {
                    i.eSelect([h]);
                }
            }
            f.prop.prev_value = d(f.elem.input).val();
            f.prop.selected_text = d(f.elem.input).val();
        }
        f.afterAction(f);
    };
    b.prototype.selectAllLine = function (f) {
        d("li", f.elem.results).each(function (g, j) {
            var h = d(j).data("dataObj");
            if (d.inArray(h, f.prop.values) === -1) {
                f.prop.values.push(h);
            }
            d(this).addClass(f.css_class.selected);
        });
        if (f.option.eSelect && d.isFunction(f.option.eSelect)) {
            f.option.eSelect(f.prop.values);
        }
        f.afterAction(f);
    };
    b.prototype.clearAll = function (f) {
        var h = f.option, g = f.elem;
        d(g.input).val("");
        d("li", g.results).each(function (j, k) {
            d(this).removeClass(f.css_class.selected);
        });
        f.prop.values.splice(0, f.prop.values.length);
        f.afterAction(f);
        if (h.eSelect && d.isFunction(h.eSelect)) {
            h.eSelect([]);
        }
    };
    b.prototype.nextLine = function (o) {
        var k = o.getCurrentLine(o), g = o.elem, n;
        if (!k) {
            n = -1;
        }
        else {
            n = d(g.results).children("li").index(k);
            d(k).removeClass(o.css_class.select);
        }
        n++;
        var p = d("li", g.results).size();
        if (n === p) {
            n = p - 1;
        }
        if (n < p) {
            var l = d(g.results).children("li").eq(n);
            d(l).addClass(o.css_class.select);
            var j = d("li:first", g.results).outerHeight(true);
            var i = d(l).position().top;
            var f = d(g.resultArea).scrollTop();
            var h = d(g.resultArea).outerHeight(true);
            var m = i + j - h;
            if ((i + j) > h) {
                d(g.resultArea).scrollTop(f + m);
            }
        }
    };
    b.prototype.prevLine = function (n) {
        var g = n.elem, m;
        var l = n.getCurrentLine(n);
        if (!l) {
            m = d(g.results).children("li").length;
        }
        else {
            m = d(g.results).children("li").index(l);
            d(l).removeClass(n.css_class.select);
        }
        m--;
        if (m < 0) {
            m = 0;
        }
        if (m > -1) {
            var k = d(g.results).children("li").eq(m);
            d(k).addClass(n.css_class.select);
            var j = d("li:first", g.results).outerHeight(true);
            var i = d(k).position().top;
            var f = d(g.resultArea).scrollTop();
            var h = d(g.resultArea).outerHeight(true);
            if (i < 0) {
                d(g.resultArea).scrollTop(f - (0 - i));
            }
        }
    };
    b.prototype.isVisible = function (f) {
        return d(f.elem.container).hasClass(f.css_class.container_open);
    };
    function c(f) {
        return this.each(function () {
            var h = d(this), g = h.data(b.dataKey), i = d.extend({}, e, h.data(), g && g.option, typeof f === "object" && f);
            if (!g) {
                h.data(b.dataKey, (g = new b(this, i)));
            }
            else {
                if (g.isVisible(g)) {
                    g.hideResults(g);
                }
                else {
                    g.showMenu(g);
                }
            }
        });
    }
    var a = d.fn.selectMenu;
    d.fn.selectMenu = c;
    d.fn.selectMenu.Constructor = b;
    d.fn.selectMenu.noConflict = function () {
        d.fn.selectMenu = a;
        return this;
    };
})(window.jQuery);

