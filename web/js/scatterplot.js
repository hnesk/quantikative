/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 04.07.13
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */
function scatterPlot() {
    var
        width = 800,
        height = 600,
        returnIndex = function (index) {
            return function (data) {
                return 1.0*data[index];
            }
        };

    function chart(selection) {
        selection.each(function(data) {

            var min = (-0.5 + Math.min(d3.min(data, returnIndex('x')), d3.min(data, returnIndex('y')))) * 0.5;
            var max = (0.5 + Math.max(d3.max(data, returnIndex('x')), d3.max(data, returnIndex('y')))) * 0.5;

            //var min = -0.5;
            //var max = 0.5;
            var scale = d3.scale.linear().range([70,530]).domain([min,max]);

            // Select the svg element, if it exists.
            var svg = d3.select(this).selectAll("svg").data([data], function (d) {return d.index;});

            svg.enter().append("svg").attr('width',width).attr('height',height)
                .append('circle')
                .attr('class','nonrelevant')
                .attr('cx',scale(0))
                .attr('cy',scale(0))
            ;

            svg.select('circle')
                .transition().duration(500)
                .attr('cx',scale(0))
                .attr('cy',scale(0))
                .attr('r',scale(0.2) - scale(0))

            var point = svg.selectAll("g").data(data, function (d) {return d.index;});
            var gEnter = point.enter().append('g')
                .attr("transform",		function (d) {return 'translate('+scale(d.x)+' '+scale(d.y)+')';})
                .attr('class',  function (d) {return d.type;});
            gEnter
                .append("circle")
                .attr("title",	function (d) {return d.short;})
                .attr("r",		function (d) {return d.type == 'term' ? 3 : 5;})
            ;
            gEnter
                .append("text")
                .attr("x",-15)
                .attr("y",15)
                .text(function (d) {return d.short;})
            ;

            point
                .transition()
                .duration(500)
                .attr("transform", function (d) {return 'translate('+scale(d.x)+' '+scale(d.y)+')';})
                .select('text').text(function (d) {return d.short;})
            ;

            point.exit()
                .remove();
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
