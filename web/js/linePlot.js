/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 04.07.13
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */
function linePlot() {
    var
        width = 400,
        height = 800
        ;

    function chart(selection) {
        selection.each(function(data) {

            var extent = d3.extent(data, util.accessFloat('value')),
                scale = d3.scale.pow().range([20,height - 20]).domain(extent),
                centerX = width / 2,
                svg = d3.select(this).selectAll("svg").data([data], function (d) {return d.id;});

            svg.enter().append("svg").attr('width',width).attr('height',height);

            var g = svg.selectAll("g").data(data, function (d) {return d.id;});
            var gEnter = g.enter().append('g')
                .attr("transform",		function (d) {return 'translate('+centerX+' '+scale(d.value)+')';})
                .attr('class',  function (d) {return d.type;});
            gEnter
                .transition()
                .duration(500)
                .style('opacity',1)
            ;

            gEnter
                .append("circle")
                .attr("title",	function (d) {return d.name;})
                .attr("r",		function (d) {return d.type == 't' ? 3 : 5;})
            ;


            gEnter
                .append("text")
                .text(function (d) {return d.name;})
            ;


            svg.selectAll('g.d text')
                .attr('text-anchor', 'end')
                .attr('x',-15);

            svg.selectAll('g.t text')
                .attr('x',15);

            /*
            point
                .transition()
                .duration(500)
                .select('text').text(function (d) {return d.name;})
            ;
            */
/*
            var gExit = point.exit();


            gExit
                .transition().duration(500)
                .style('opacity',0)
                .remove()
                .select('circle')
                    .transition()
                    .duration(500)
                    .attr("r",0)
                    .remove();
            ;
*/
        });
    }


    chart.width = function(_) {
        if (!arguments.length) return width;
        width = _;
        return chart;
    };

    chart.height = function(_) {
        if (!arguments.length) return height;
        height = _;
        return chart;
    };


    return chart;
}
