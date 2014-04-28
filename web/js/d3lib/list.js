d3.lib = d3.lib || {};
d3.lib.list = function () {
    var table,
        tbody,
        dispatch = d3.dispatch('change'),
        timeFormat = d3.time.format('%a, %d.%m.%y %H:%M')
    ;


    function exports(_selection) {
        _selection.each(function (data) {

            if (!table) {
                table = d3.select(this).append("table").attr('class', 'listChart table table-condensed table-bordered table-striped');
                tbody = table.append('tbody');
            }

            var tr = tbody.selectAll('tr').data(data, util.access('id')),
                trEnter = tr.enter().append('tr'),
                trExit = tr.exit().remove()
            ;

            tr.sort(function(a, b) {
                return a.date < b.date ? -1 : a.date > b.date ? 1 : a.date >= b.date ? 0 : NaN;
            });

            tr.order();

            trEnter
                .append('td')
                .classed('time',true)
                .text(function (d) {return timeFormat(d.date);});

            trEnter
                .append('td')
                .classed('reason',true)
                .text(util.access('reason'));

            trEnter
                .append('td')
                .classed('type',true)
                .text(util.access('type'));

            trEnter
                .append('td')
                .classed('code',true)
                .text(util.access('code'));

            trEnter
                .append('td')
                .classed('category',true)
                .text(util.access('category'));

            trEnter
                .append('td')
                .classed('address',true)
                .text(function(d) { return d.location.address;});


            trEnter
                .append('td')
                .classed('participants',true)
                .text(' ');

        });
    }


    d3.rebind(exports, dispatch, "on");

    return exports;

}
