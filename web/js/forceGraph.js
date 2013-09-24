/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 24.09.13
 * Time: 23:00
 * To change this template use File | Settings | File Templates.
 */
function forceGraph(root,data) {


    var width = 1000,
        height = 600,
        distance = d3.scale.linear()
            .domain([-1,1])
            .range([650, 0]),
        colors = d3.scale.linear()
            .domain([-1, 0, 1])
            .range(['#E66101', '#efeff8', '#5E3C99']),
        strokeWidth = d3.scale.linear()
            .domain([-1,-0.2,0.2,1])
            .range([7,0,0,7]),
        zIndex= d3.scale.linear()
            .domain([-1,1])
            .rangeRound([1,1000]),

        graph = root.append('svg')
            .attr('width',width)
            .attr('height',height),

        force = d3.layout.force()
            .gravity(.05)
            .charge(-100)
            .size([width, height])
        ;


    var link = graph.selectAll('line.link')
            .data(data.links)
            .enter()
            .append('line')
            .attr('class','link')
            .style('stroke', util.compose(colors, util.access('similarity')))
            .style('stroke-width', util.compose(strokeWidth, util.access('similarity')))
            .style('z-index', util.compose(zIndex, util.access('similarity')))
        ;

    var node = graph.selectAll('g.node')
            .data(data.nodes)
            .enter()
            .append('g')
            .attr('class','node')
            .call(force.drag)
        ;

    node.append('circle').attr('r',5);
    node.append('text')
        .attr("dx", 12)
        .attr("dy", ".35em")
        .text(function(d) { return d.short});

    force
        .linkDistance(function (l) {
            return distance(l.similarity);
        })
        .nodes(data.nodes)
        .links(data.links)
        .start()
        .on("tick", function() {
            link.attr("x1", function(d) { return d.source.x; })
                .attr("y1", function(d) { return d.source.y; })
                .attr("x2", function(d) { return d.target.x; })
                .attr("y2", function(d) { return d.target.y; });

            node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
        });

}

