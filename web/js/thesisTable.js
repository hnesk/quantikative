
var thesisTable = {
    answers : [],

    rows : [],
    headrow : null,

    init: function (rootSelection, theses, answers) {
        this.answers = answers;
        var table = rootSelection.append("table").attr('class', 'thesisTable'),
            thead = table.append("thead"),
            tbody = table.append("tbody")
        ;

        this.headrow = thead.append('tr');

        this.headrow.selectAll('th').data(['Partei 1', 'Partei 2', 'Aussage'])
            .enter()
            .append('th')
            .attr('class', function(d,i) {return 'partyhead partyhead-'+i;})
            .text(util.identity);

        this.rows = tbody.selectAll('tr').data(theses);
        var enterRows = this.rows.enter().append('tr');

        enterRows.append('td').attr('class', 'party-0');
        enterRows.append('td').attr('class', 'party-1');;
        enterRows.append('th').text(util.access('short')).attr('title', util.access('long'));

    },


    select: function (party0Index, party0,party1Index, party1) {
        var answers = d3.zip(this.answers[party0Index],this.answers[party1Index]);
        var rows = this.rows.data(answers)
        rows.select('td.party-0').text(function (d,i) {return d[0];});
        rows.select('td.party-1').text(function (d,i) {return d[1];});

        this.headrow.select('.partyhead-0').text(party0.short);
        this.headrow.select('.partyhead-1').text(party1.short);
    }

};
