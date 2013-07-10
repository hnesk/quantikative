/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 04.07.13
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */
function multiSelect() {

    var chartName = 'term',
        onchange = function() {},
        returner = function (i) {return function (d) {return d[i];}},
        itemIndex = returner('index'),
        itemText = returner('short'),
        itemTitle = returner('long'),
        selection = [],
        itemName = function(d) {return chartName+'['+ itemIndex(d)+']';};


    ;


    function chart(currentSelection) {
        selection = currentSelection[0][0];
        currentSelection.each(function(data) {
            var list = d3.select(this).selectAll('ul').data([data], function (d) {return d.index;});
            list.enter().append("ul").attr('class', chartName+' multiselect');

            var items = list.selectAll('li').data(data, itemIndex);
            items.enter()
                .append('li')
                    .append('label')
                        .text(itemText)
                        .attr('title', itemTitle)
                    .append('input')
                        .attr('type', 'checkbox')
                        .attr('name', itemName)
                        .attr('value', '1')
                        .on('change', onchange)
                        .checked(1);

            items.exit()
                .checked(0);
        });
    }

    chart.cssClass = function(value) {
        if (!arguments.length) return cssClass;
        cssClass = value;
        return chart;
    };

    chart.index = function(value) {
        if (!arguments.length) return itemIndex;
        itemIndex = value;
        return chart;
    };

    chart.title = function(value) {
        if (!arguments.length) return itemTitle;
        itemTitle = value;
        return chart;
    };

    chart.onchange = function(value) {
        if (!arguments.length) return onchange;
        onchange = value;
        return chart;
    };

    chart.chartName = function(value) {
        if (!arguments.length) return chartName;
        chartName = value;
        return chart;
    };

    chart.values = function() {
        var result = {};
        d3.select(selection).selectAll('li input:checked').each(function (d, i) {
            result[itemName(d,i)] = 1;
        });
        return result;
    }

    return chart;
}
