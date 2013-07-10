/**
 * Created with JetBrains PhpStorm.
 * User: jk
 * Date: 11.07.13
 * Time: 01:11
 * To change this template use File | Settings | File Templates.
 */

d3.selection.prototype.checked = function(value) {
    return arguments.length < 1 ? this.property("checked") : this.property("checked", value);
};

function buildUrl(base, arguments) {
    var argumentStrings = [];
    arguments = arguments || {};
    for (var i in arguments) {
        argumentStrings.push(i + '=' + arguments[i]);
    }
    return base + (argumentStrings.length > 0 ? '?' +  argumentStrings.join('&') : '');
};


function wahlomat(apiBaseUrl) {
    this.chart = scatterPlot();
    this.partySelect = multiSelect().chartName('party').onchange(refresh);
    this.thesisSelect = multiSelect().chartName('term').onchange(refresh);

    var self = this;

    function init(filter) {
        d3.json(
            buildUrl(apiBaseUrl, filter),
            function(data) {
                d3.select("#scatter").datum(data.plot).call(self.chart);
                d3.select("#partySelect").datum(data.parties).call(self.partySelect);
                d3.select("#thesisSelect").datum(data.terms).call(self.thesisSelect);
            }
        );
    }

    function refresh() {
        init(jQuery.extend(self.partySelect.values(), self.thesisSelect.values()));
    }

    init();

}








