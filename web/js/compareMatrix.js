
var partyMatrix = {

    colors : d3.scale.linear()
            .domain([-1, 0, 1])
            .range(['#ff8800', '#efeff8', '#00A0ff']),

    colorsText : d3.scale.linear()
        .domain([-1, 0, 1])
        .range(['#000000', '#cccccc', '#000000']),


    tableHighlight : function(table, color, cursorColor) {
        return function(d,i) {
            var column = i;
            var row = this.parentNode.dataset.index;
            table.selectAll('tr.row-'+row+' td.item, td.item.column-'+column).transition().style('border-color',color);
            table.selectAll('tr.row-'+row+' th, th.column-'+column).transition().style('background-color',color);
            table.selectAll('tr.row-'+row+' td.item.column-'+column).transition().style('border-color',cursorColor);
        }
    },

    tableHighlightColor : '#cccccc',
    cursorHighlightColor : '#333333',
    tableDefaultColor : '#efeff8',


    dispatcher : null,


    init : function (rootSelection, values, columnData, rowData) {

        var table = rootSelection.append("table").attr('class', 'matrix partyMatrix'),
            thead = table.append("thead"),
            headrow = thead.append('tr'),
            tbody = table.append("tbody"),

            highlightOn = partyMatrix.tableHighlight(table, partyMatrix.tableHighlightColor, partyMatrix.cursorHighlightColor),
            highlightOff = partyMatrix.tableHighlight(table, partyMatrix.tableDefaultColor, partyMatrix.tableDefaultColor),
            highlightCurrent = partyMatrix.tableHighlight(table, '#333', '#000'),
            minValue = d3.min(values, function (row) {return d3.min(row);}),
            maxValue = d3.max(values, function (row) {return d3.max(row);}),
            self = this;


        this.colors.domain([minValue, 0, maxValue]);
        this.colorsText.domain([minValue, 0, maxValue]);
        this.dispatcher = d3.dispatch('selected','unselected');

        function buildCell(selection) {
            selection
                .attr('title',function (d,i) {
                    return columnData[i].short + ': ' + rowData[this.parentNode.dataset.index].short;
                    // (d > 0 ? 'Ja' : (d < 0 ? 'Nein' : '---')) + ' : ' +
                })
                .attr('class',function (d,i) {return 'item column-'+i ;})
                .style('background-color', this.colors)
                .style('color', this.colorsText)
                .on('mouseover' , highlightOn)
                .on('click' , function(d,i) {
                    self.dispatcher.selected.apply(this, [this.parentNode.dataset.index, i]);
                    highlightOn.apply(this, [d,i]);
                })
                .on('mouseout' , function(d,i) {
                    self.dispatcher.unselected.apply(this, [this.parentNode.dataset.index, i]);
                    highlightOff.apply(this, [d,i]);
                })
                .text(function (d) {return (d > 0 ? '+' : (d < 0 ? '-' : '~'));})
            ;
        }



        headrow.selectAll('th').data(columnData).enter()
            .append('th')
                .attr('class',function (d,i) {return 'column-' + i;})
                .append('span')
                    .text(util.access('short'))
                    .attr('title', util.access('long'));


        headrow.insert('td',':first-child');


        var rows = tbody.selectAll('tr').data(rowData).enter()
                .append('tr')
                .datum(function (d,i) {this.dataset.index = i;return d;})
                .attr('class', function (d,i) {return 'row-'+i;})
            ;


        var cells = rows.selectAll('td').data(function (d,i) {return values[i];}).call(buildCell.bind(this));
        cells.enter().append('td').call(buildCell.bind(this));


        rows.data(rowData)
            .insert('th',':first-child')
                .append('span')
                    .text(util.access('short'))
                    .attr('title', util.access('long'));
    }
}