d3.lib = d3.lib || {};
d3.lib.map = function () {
    var height = 120,
        width = 220,
        svg,
        g,
        map,
        dispatch = d3.dispatch('change')
    ;


    function exports(_selection) {
        _selection.each(function (data) {

            d3.select(this).style({
                width: width+'px',
                height: height+'px'
            });

            if (!map) {
                map = new L.Map(this.id)
                    .addLayer(new L.TileLayer("http://{s}.tile.cloudmade.com/00e9fbfd433c41bfacf51b5888103be6/998/256/{z}/{x}/{y}.png"))
                    .setView(new L.LatLng(52.0167, 8.5167), 12);
                map._initPathRoot();
                /* We simply pick up the SVG from the map object */
                svg = d3.select(map.getPanes().overlayPane).select("svg");
                g = svg.append("g"); //.attr("class", "leaflet-zoom-hide");

            }





            /* Define the d3 projection */
            var buildCircle = function (_selection) {
                var point;
                _selection.each(function (d,i) {
                    point = map.latLngToLayerPoint(new L.LatLng(d.location.lat, d.location.lon));
                    d3.select(this)
                        .attr('cx', point.x)
                        .attr('cy', point.y);

                });

            };

            var circles = g.selectAll("circle").data(data, util.access('id'));

            circles.enter().append("circle")
                .attr('r', function(d,i) {console.log(this); return 3;})
                .attr('fill', 'rgba(0,0,0,0.7)')
                .attr('title', util.access('id'));

            circles.call(buildCircle);
            circles.exit().remove();


            map.on("viewreset", function() {
                circles.call(buildCircle);
            });
        });
    }

    exports.height = function (_) {
        if (!arguments.length) return height;
        height = _;
        return this;
    };

    exports.width = function (_) {
        if (!arguments.length) return width;
        width = _;
        return this;
    };


    d3.rebind(exports, dispatch, "on");

    return exports;

}
