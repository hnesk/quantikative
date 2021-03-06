/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 21.08.13
 * Time: 21:57
 * To change this template use File | Settings | File Templates.
 */

var similarityMatrix = {

    colors : d3.scale.linear()
            .domain([-1, 0, 1])
            //.range(['#F1A340', '#efeff8', '#998EC3']),
            .range(['#E66101', '#efeff8', '#5E3C99']),
            //0xE66101; 0xFDB863; 0xF7F7F7; 0xB2ABD2; 0x5E3C99;
            //.range(['#ff8800', '#efeff8', '#00A0ff']),

    colorsText : d3.scale.linear()
        .domain([-1, 0, 1])
        .range(['#000000', '#cccccc', '#000000']),

    numberFormat : d3.format('.2f'),

    tableHighlight : function(table, color, cursorColor) {
        return function(d,i) {
            var column = i;
            var row = this.parentNode.dataset.index;
            table.selectAll('tr.row-'+row+' td.item, td.item.column-'+column).style('border-color',color);
            table.selectAll('tr.row-'+row+' th, th.column-'+column).style('background-color',color);
            table.selectAll('tr.row-'+row+' td.item.column-'+column).style('border-color',cursorColor);
        }
    },

    tableHighlightColor : 'rgb(204,204,204)',
    cursorHighlightColor : 'rgb(51,51,51)',
    tableDefaultColor : 'rgb(239,239,248)',


    dispatcher : null,


    init : function (rootSelection, values, documents) {

        var table = rootSelection.append("table").attr('class', 'matrix similarityMatrix'),
            thead = table.append("thead"),
            headrow = thead.append('tr'),
            tbody = table.append("tbody"),

            highlightOn = similarityMatrix.tableHighlight(table, similarityMatrix.tableHighlightColor, similarityMatrix.cursorHighlightColor),
            highlightOff = similarityMatrix.tableHighlight(table, similarityMatrix.tableDefaultColor, similarityMatrix.tableDefaultColor),
            highlightCurrent = similarityMatrix.tableHighlight(table, '#fff', '#000'),
            minValue = d3.min(values, function (row) {return d3.min(row);}),
            maxValue = d3.max(values, function (row) {return d3.max(row);}),
            self = this;


        this.colors.domain([minValue, 0, maxValue]);
        this.colorsText.domain([minValue, 0, maxValue]);
        this.dispatcher = d3.dispatch('selected');

        headrow.selectAll('th').data(documents)
            .enter()
            .append('th')
            .attr('class',function (d,i) {return 'column-' + i;})
            .append('span').text(function (d) {return d.short;});

        headrow.insert('td',':first-child');


        var rows = tbody.selectAll('tr').data(values.slice(0).reverse()).enter()
                .append('tr')
                .datum(function (d,i) {this.dataset.index = values.length - i - 1;return d;})
                .attr('class', function (d,i) {return 'row-'+this.dataset.index})
            ;

        var cells = rows.selectAll('td').data(function (row) {return row;});

        var cellsEnter = cells.enter().append('td');

        cellsEnter
            //.filter(function (d,i) {return  i <= this.parentNode.dataset.index;})
            .datum(function (d,i) {return d;})
            .attr('title',function (d,i) {return documents[this.parentNode.dataset.index].short + '   /   ' + documents[i].short + '  =  ' + self.numberFormat(d);})
            .attr('class',function (d,i) {return 'item column-'+i ;})
            .text(this.numberFormat)
            .on('mouseout' , highlightOff)
            .on('mouseover' , highlightOn)
            .on('click' , function(d,i) {
                self.dispatcher.selected.apply(this, [this.parentNode.dataset.index, documents[this.parentNode.dataset.index], i, documents[i]]);
                highlightCurrent.apply(this, [d,i]);
            })
            .style('background-color', this.colors)
            .style('color', this.colorsText);


        rows.data(documents.slice(0).reverse()).insert('th',':first-child').append('span').text(function (d) {return d.short;});
    }
}