d3.lib = d3.lib || {};
d3.lib.barChart = function() {
    var margin = {top: 20, right: 10, bottom: 20, left: 10},
        height = 120,
        width = 220,
        xScale = d3.scale.ordinal(),
        yScale = d3.scale.linear(),
        xAxis = d3.svg.axis()
            .tickSize(0)
            .orient("bottom"),
        yAxis,
        dimension,
        svg,
        dispatch = d3.dispatch('barOver','barOut','barClick','labelClick','change')
            .on('barClick._internal', function (d) {
                var e = d3.select(this),
                    selected;

                if (e.classed('tick')) {
                    e = svg.select('g.bar.index-'+d);
                }

                e.classed('selected', !e.classed('selected'));

                selected = svg.selectAll('.selected');
                dispatch.change({
                    dimension: dimension,
                    selected: selected,
                    keys: selected.data().map(util.access('key')),
                    current: e,
                    state: e.classed('selected')
                });

            })
            .on('labelClick._internal', function (d, i) {
                var bar = svg.selectAll('g.bar.index-'+d);
                dispatch.barClick.apply(bar.node(), [bar.datum(), d]);
            });


    ;


    function exports(_selection) {
        _selection.each(function(data) {
            var innerWidth  = width - margin.left - margin.right;

            if (!svg) {
                svg = d3.select(this)
                    .append("svg")
                    .classed("chart", true);

                var container = svg.append("g").classed('container', true);
                container.append("g").classed('chart', true);
                container.append("g").classed('x axis', true);
                container.append("g").classed('y axis', true);

            }

            svg.transition()
                .attr("width", width)
                .attr("height", height);

            xScale
                .domain(data.map(util.key))
                .rangeRoundBands([0, innerWidth], 0.2, 0);
            yScale
                .domain([0, d3.max(data, util.value)])
                .range([0, height - margin.bottom - margin.top]);

            xAxis.scale(xScale);

            svg.select(".container")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            svg.select(".x.axis")
                .attr("transform", "translate(0," + (height - margin.bottom - margin.top)+ ")")
                .call(xAxis)
                .selectAll('g.tick')
                    .on('click.bar',dispatch.labelClick)
            ;


            var bars = svg.select(".chart")
                .selectAll(".bar")
                .data(data, util.key);

            var barsEnter = bars.enter().append('g')
                .attr('class', function (d,i) {return 'bar index-'+ d.key;})
                .on('click.bar',dispatch.barClick)
            ;


            barsEnter.append('rect');
            barsEnter.append('text').text(util.value);

            bars.select('rect').transition()
                .attr({
                    x: X,
                    y : function (d) {return height - margin.bottom - margin.top - Y(d);},
                    width: xScale.rangeBand(),
                    height: Y
                });


            bars.select('text').transition()
                .attr({
                    x:X,
                    y: function (d) {return height - margin.bottom - margin.top - Y(d);},
                    dx: xScale.rangeBand() / 2,
                    dy: "-.35em",
                    'text-anchor':'middle'
                })
                .text(util.value);

        });
    }

    // The x-accessor for the path generator; xScale xValue.
    function X(d) {
        return xScale(d.key);
    }

    // The x-accessor for the path generator; yScale yValue.
    function Y(d) {
        return Math.ceil(yScale(d.value));
    }

    exports.margin = function(_) {
        if (!arguments.length) return margin;
        margin = _;
        return this;
    };

    exports.xAxis= function(_) {
        if (!arguments.length) return xAxis;
        xAxis = _;
        return this;
    };


    exports.height = function(_) {
        if (!arguments.length) return height;
        height = _;
        return this;
    };

    exports.width = function(_) {
        if (!arguments.length) return width;
        width = _;
        return this;
    };


    exports.dimension = function(_) {
        if (!arguments.length) return dimension;
        dimension = _;
        return this;
    };




    d3.rebind(exports, dispatch, "on");

    return exports;

}
