/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 04.07.13
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */
function linePlot() {
    var
        margin = {top: 20, right: 20, bottom: 30, left: 40},
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom,
        xValue = function(d,i) { return i; },
        yValue = function(d,i) { return d; },
        xScale = d3.scale.linear(),
        yScale = d3.scale.linear(),

        xAxis = d3.svg.axis().scale(xScale).orient("bottom").tickSize(10,1,1),
        yAxis = d3.svg.axis().scale(yScale).orient("left").tickSize(10,1,1),

        area = d3.svg.area().x(X).y1(Y),
        line = d3.svg.line().x(X).y(Y)
     ;



    function chart(selection) {
        selection.each(function(data) {
            // Update the x-scale.
            xScale.domain(d3.extent(data, xValue)).range([0, width - margin.left - margin.right]);

            // Update the y-scale.
            yScale.domain([0, d3.max(data, yValue)]).range([height - margin.top - margin.bottom, 0]);

            // Select the svg element, if it exists.
            var svg = d3.select(this).selectAll("svg").data([data], function (d,i) {return d[0];});

            var gEnter = svg.enter()
                .append("svg").attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)
                .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");


            gEnter.append("g").attr("class", "x axis");
            gEnter.append("g").attr("class", "y axis");

            gEnter.append("path")
                .attr("class", "line")
                .attr("d", function(d) { return line(d); });
            gEnter.append("path")
                .attr("class", "area")
                .attr("d", area.y0(yScale.range()[0]));


            // Update the x-axis.
            gEnter.select(".x.axis")
                .attr("transform", "translate(0," + yScale.range()[0] + ")")
                .call(xAxis);

            // Update the x-axis.
            gEnter.select(".y.axis").call(yAxis);

        });
    }

            /*

            svg.enter().append("svg").attr('width',width).attr('height',height);

            var svg = d3.select("body").append("svg")

            d3.tsv("data.tsv", type, function(error, data) {
                x.domain(data.map(function(d) { return d.letter; }));
                y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

                svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

                svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis)
                    .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Frequency");

                svg.selectAll(".bar")
                    .data(data)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", function(d) { return x(d.letter); })
                    .attr("width", x.rangeBand())
                    .attr("y", function(d) { return y(d.frequency); })
                    .attr("height", function(d) { return height - y(d.frequency); });




                var min = Math.min(d3.min(data, returnIndex('x')), d3.min(data, returnIndex('y')));
            var max = Math.max(d3.max(data, returnIndex('x')), d3.max(data, returnIndex('y')));

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
        */
    // The x-accessor for the path generator; xScale ∘ xValue.
    function X(d,i) {
        return xScale(i);
    }

    // The x-accessor for the path generator; yScale ∘ yValue.
    function Y(d) {
        return yScale(d);
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
