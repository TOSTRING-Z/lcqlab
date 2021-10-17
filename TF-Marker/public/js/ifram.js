$(document).ready(() => {
    $("#loading").css("visibility", "hidden");
    $(".dropdown-menu a").click((e) => {
        $('#title').text(e.target.innerText);
        $("#loading").css("visibility", "visible");
    });
    $('.table').each((i, table) => {
        $(table).dataTable({
            dom: '<"top row"<"col-md-6 col-xs-6 pull-left"iB><"col-md-6 col-xs-6 pull-right"f>>rt<"bottom row"<"col-md-4 col-xs-4 pull-left"l><"col-md-8 col-xs-8 pull-right"p>><"clear">',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="ri-folder-download-line"></i>'
            }],
            scrollX: true,
            colReorder: true,
            createdRow: function (row, data, dataIndex) {
                $(row).children('td').each((i, e) => {
                    if (e.innerHTML == '') {
                        $(e).html('\\')
                    }
                })
            }
        });
    })
});

function openView(Enhancer, Overlap_genes, Proximal_genes, Closest_gene, title, Description, position) {
    if (Proximal_genes == "") {
        Proximal_genes = "/";
    }
    if (Closest_gene == "") {
        Closest_gene = "/";
    }
    if (Overlap_genes == "") {
        Overlap_genes = "/";
    }
    this.open = function () {
        if (title == "SNV") {
            title = "Location";
            $("#info").html(
                "<tr><td>" + title + "</td><td>" + Enhancer + "</td></tr>" +
                "<tr><td>Overlap_genes</td><td>" + Overlap_genes + "</td></tr>" +
                "<tr><td>Proximal_genes</td><td>" + Proximal_genes + "</td></tr>" +
                "<tr><td>Closest_gene</td><td>" + Closest_gene + "</td></tr>"
            );
        } else {
            if (position)
                $("#info").html(
                    "<tr><td>" + title + "</td><td>" + Enhancer + "</td></tr>" +
                    "<tr><td>Location</td><td>" + position + "</td></tr>" +
                    "<tr><td>Overlap_genes</td><td>" + Overlap_genes + "</td></tr>" +
                    "<tr><td>Proximal_genes</td><td>" + Proximal_genes + "</td></tr>" +
                    "<tr><td>Closest_gene</td><td>" + Closest_gene + "</td></tr>"
                );
            else
                $("#info").html(
                    "<tr><td>" + title + "</td><td>" + Enhancer + "</td></tr>" +
                    "<tr><td>Description</td><td>" + Description + "</td></tr>" +
                    "<tr><td>Overlap_genes</td><td>" + Overlap_genes + "</td></tr>" +
                    "<tr><td>Proximal_genes</td><td>" + Proximal_genes + "</td></tr>" +
                    "<tr><td>Closest_gene</td><td>" + Closest_gene + "</td></tr>"
                );
        }
        var myChart = echarts.init(document.getElementById('view'));
        var links = [], data = [];
        var onlys = [];
        data.push({
            category: 0,
            id: title,
            name: Enhancer,
            symbolSize: 20,
            value: 1
        }, {
            category: 1,
            id: "Overlap",
            name: "Overlap",
            symbolSize: 20,
            value: 1
        }, {
            category: 1,
            id: "Proximal",
            name: "Proximal",
            symbolSize: 20,
            value: 1
        }, {
            category: 1,
            id: "Closest",
            name: "Closest",
            symbolSize: 20,
            value: 1
        });
        console.log(data);
        links.push({source: title, target: "Overlap"}, {source: title, target: "Proximal"}, {
            source: title,
            target: "Closest"
        })
        for (element in Overlap_genes.split(",")) {
            if (Overlap_genes.split(",")[element] == "") continue;
            links.push({source: "Overlap", target: Overlap_genes.split(",")[element]});
            if (onlys.indexOf(Overlap_genes.split(",")[element]) != -1) continue;
            onlys.push(Overlap_genes.split(",")[element]);
            data.push({
                category: 2,
                id: Overlap_genes.split(",")[element],
                name: Overlap_genes.split(",")[element],
                symbolSize: 20,
                value: 1
            });
        }
        for (element in Proximal_genes.split(",")) {
            if (Proximal_genes.split(",")[element] == "") continue;
            links.push({source: "Proximal", target: Proximal_genes.split(",")[element]});
            if (onlys.indexOf(Proximal_genes.split(",")[element]) != -1) continue;
            onlys.push(Proximal_genes.split(",")[element]);
            data.push({
                category: 2,
                id: Proximal_genes.split(",")[element],
                name: Proximal_genes.split(",")[element],
                symbolSize: 20,
                value: 1
            });
        }

        for (element in Closest_gene.split(",")) {
            if (Closest_gene.split(",")[element] == "") continue;
            links.push({source: "Closest", target: Closest_gene.split(",")[element]});
            if (onlys.indexOf(Closest_gene.split(",")[element]) != -1) continue;
            onlys.push(Closest_gene.split(",")[element]);
            data.push({
                category: 2,
                id: Closest_gene.split(",")[element],
                name: Closest_gene.split(",")[element],
                symbolSize: 20,
                value: 1
            });
        }

        option = {
            legend: {
                data: [title, 'Gene type', 'Gene']
            },
            animationDuration: 1500,
            animationEasingUpdate: 'quinticInOut',
            series: [{
                type: 'graph',
                layout: 'force',
                animation: false,
                roam: true,
                focusNodeAdjacency: true,
                itemStyle: {
                    normal: {
                        borderColor: '#fff',
                        borderWidth: 1,
                        shadowBlur: 10,
                        shadowColor: 'rgba(0, 0, 0, 0.3)'
                    }
                },
                label: {
                    normal: {
                        show: true,
                        position: 'right',
                        formatter: '{b}'
                    }
                },
                lineStyle: {
                    normal: {
                        color: 'source',
                        curveness: 0
                    }
                },
                emphasis: {
                    normal: {
                        lineStyle: {
                            width: 10
                        }
                    }
                },
                draggable: true,
                data: data,
                links: links,
                categories: [{name: title}, {name: 'Gene type'}, {name: 'Gene'}],
                force: {
                    repulsion: 200,
                    gravity: 0.1,
                    edgeLength: 100,
                    layoutAnimation: true
                }
            }]
        };

        myChart.setOption(option);
    }
    this.open();
    $('#myModal').modal('toggle').on('shown.bs.modal');
    $("#myModalLabel").text(Enhancer);
}

function draw(edata, mdata, ddata, link, rsid) {
    $('#jplot').html("");
    $('#jplot').html("<line x1='0' y1='110' x2='100%' y2='110' style='stroke:rgb(0,0,0);stroke-width:2'/>")
    var dhtml = "<svg width='100%' height='100%'>";
    dhtml += "<defs><marker id='arrow' markerWidth='10' markerHeight='10' refX='0' refY='3' orient='auto' markerUnits='strokeWidth'><path d='M0,0 L0,6 L9,3 z' fill='#000' /></marker></defs>";
    var mst = mdata.split("\n");
    var ms = mst[0].split("\t");
    var add = ((Number(ms[2]) - Number(ms[1])) * 0.02);
    var minv = (Number(ms[1]) - add);
    var maxv = (Number(ms[2]) + add);
    var unit = (maxv - minv) / 100;
    dhtml += "<text x='50%' y='10' text-anchor='middle' fill='black' font-size = '80%'>" + ms[0] + ":" + ms[1] + "-" + ms[2] + "</text>";
    dhtml += "<line x1='2%' y1='5' x2='2%' y2='15' style='stroke:black;stroke-width:1' />";
    dhtml += "<line x1='2%' y1='10' x2='40%' y2='10' style='stroke:black;stroke-width:1' />";
    dhtml += "<line x1='98%' y1='5' x2='98%' y2='15' style='stroke:black;stroke-width:1' />";
    dhtml += "<line x1='60%' y1='10' x2='98%' y2='10' style='stroke:black;stroke-width:1' />";


    var erows = edata.split("\n");

    erows.splice(-1, 1);
    erows.forEach(function getvalues(eourrow) {
        var ecolumns = eourrow.split("\t");
        var ecenter = (ecolumns[2] / 2 + ecolumns[1] / 2);

        if (ddata) {
            var drows = ddata.split("\n");
            drows.splice(-1, 1);
            drows.forEach(function getvalues(dourrow) {
                var dcolumns = dourrow.split("\t");

                if (dcolumns[3] == "E") {
                    console.log(dcolumns);
                    dhtml += "<line x1='" + (ecenter - minv) / unit + "%' y1='100' x2='" + (ecenter - minv) / unit + "%' y2='110' style='stroke:blue;stroke-width:5' />";
                    //tf
                    if (dcolumns[5] != "") {
                        dhtml += "<g class='h'>";
                        dhtml += "<rect x='" + (ecenter - minv) / unit + "%' y='115' width='1' height='10' style='fill:purple' />";
                        dhtml += "<text x='" + (ecenter - minv) / unit + "%' y='120' text-anchor='middle' fill='black' font-size = '60%'>" + dcolumns[5] + "</text>";
                        dhtml += "</g>";
                    }
                    //SNV
                    dhtml += "<g>";
                    dhtml += "<rect x='" + (ecenter - minv) / unit + "%' y='145' width='1' height='10' style='fill:red' />";
                    dhtml += "<text x='" + (ecenter - minv) / unit + "%' y='165' text-anchor='middle' fill='red' font-size = '60%'>" + rsid + "</text>";
                    dhtml += "</g>";
                    //gwas
                    if (dcolumns[7] != "") {
                        dhtml += "<g class='h'>";
                        dhtml += "<rect x='" + (ecenter - minv) / unit + "%' y='130' width='1' height='10' style='fill:rgb(0,255,0)' />";
                        dhtml += "<text x='" + (ecenter - minv) / unit + "%' y='135' text-anchor='middle' fill='black' font-size = '60%'>" + dcolumns[7] + "</text>";
                        dhtml += "</g>";
                    }
                } else {
                    if (dcolumns[5] != "")
                        var color = 'purple';
                    else
                        color = 'teal';
                    dhtml += "<g class='h'>";
                    //dhtml += "<line x1='" + (dcolumns[1] - minv) / unit + "%' y1='100' x2='" + (dcolumns[1] - minv) / unit + "%' y2='110' style='stroke:" + color + ";stroke-width:1' />";
                    dhtml += "<rect x='" + (dcolumns[1] - minv) / unit + "%' y='100' width='1' height='10' style='fill:" + color + "' />";
                    dhtml += "<text x='" + (dcolumns[1] - minv) / unit + "%' y='105' text-anchor='middle' fill='black' font-size = '60%'>" + dcolumns[3] + "</text>";
                    dhtml += "</g>";
                    dhtml += "<rect x='" + (dcolumns[1] - minv) / unit + "%' y='110' width='1' height='20' style='fill:rgb(0,0,0)' />";
                    //dhtml += "<line x1='" + (dcolumns[1] - minv) / unit + "%' y1='110' x2='" + (dcolumns[1] - minv) / unit + "%' y2='130' style='stroke:rgb(0,0,0);stroke-width:1' />";
                    if (dcolumns[4] == "+") {
                        dhtml += "<line x1='" + (dcolumns[1] - minv) / unit + "%' y1='130' x2='" + ((dcolumns[1] - minv) / unit + 1) + "%' y2='130' style='stroke:rgb(0,0,0);stroke-width:1;' marker-end='url(#arrow)' />";
                    } else {
                        dhtml += "<line x1='" + (dcolumns[1] - minv) / unit + "%' y1='130' x2='" + ((dcolumns[2] - minv) / unit - 1) + "%' y2='130' style='stroke:rgb(0,0,0);stroke-width:1;' marker-end='url(#arrow)' />";
                    }

                    if (dcolumns[9] == 1) {
                        dhtml += "<a style='cursor: pointer;' xlink:href='somatic-mutation.php?cells=" + "cells" + "&gene=" + dcolumns[3] + "' target='_blank'>";
                        dhtml += "<text x='" + (dcolumns[1] - minv) / unit + "%' y='150' text-anchor='middle' fill='black' font-size = '60%' class='xxxxx'>" + dcolumns[3] + "<tspan y='140' class='yyyyy'>click to view mutation</tspan></text>";
                        dhtml += "</a>";
                    } else {
                        dhtml += "<text x='" + (dcolumns[1] - minv) / unit + "%' y='150' text-anchor='middle' fill='black' font-size = '60%'>" + dcolumns[3] + "</text>";
                    }


                    if (dcolumns[5] == "Y") {
                        dhtml += "<path d='M " + ((dcolumns[1] - minv) / unit) * 10 + " 110 Q " + ((dcolumns[1] / 2 + ecenter / 2 - minv) / unit) * 10 + " -30 " + ((ecenter - minv) / unit) * 10 + " 110' stroke='black' stroke-width='1' fill='none' />";
                    }

                    if (dcolumns[8] != "") {
                        dhtml += "<path d='M " + ((dcolumns[1] - minv) / unit) * 10 + " 110 Q " + ((dcolumns[1] / 2 + ecenter / 2 - minv) / unit) * 10 + " -40 " + ((ecenter - minv) / unit) * 10 + " 110' stroke='blue' stroke-width='1' fill='none' />";
                    }
                    //link
                    if (link) {
                        link.forEach((item, index) => {
                            dhtml += "<path d='M " + ((item - minv) / unit) * 10 + " 110 Q " + ((item / 2 + ecenter / 2 - minv) / unit) * 10 + " -30 " + ((ecenter - minv) / unit) * 10 + " 110' stroke='black' stroke-width='1' fill='none' />";
                        })
                    }
                }
            });
        }
        dhtml += "</svg>";
        $('#jplot').append(dhtml);
    });
}

function plot(chr, rsid, start, end, genes) {
    $.ajax({
        url: "search_result_detail_related_genes_svg_view.php",
        data: {
            "chr": chr,
            "start": start,
            "end": end,
            "rsid": rsid,
            "genes": genes.replace(/@/g, "\"")
        },
        success: (data) => {
            console.log(data);
            $('#myModal_jplot').modal('toggle').on('shown.bs.modal', function () {
                draw(data.edata, data.mdata, data.ddata, data.link, rsid);
            })
        }
    })
}