
var thesisCloud = {
    answers : [],
    theses : [],
    matchList: null,
    nonMatchList: null,

    init: function (rootSelection, theses, answers) {
        this.answers = answers;
        this.theses = theses;

        this.matchList = rootSelection.append("ul").attr('class', 'thesisCloud match');
        this.nonMatchList = rootSelection.append("ul").attr('class', 'thesisCloud nonmatch');
    },


    select: function (party0Index, party0, party1Index, party1) {
        var matchingTheses = [],
            nonMatchingTheses = []
        ;


        function updateCloud(base) {
            function updateItem(li) {
                li.html('');
                li.append('span').classed('party0',true).text(function (d) {return util.showSign(d.parties[0].answer);});
                li.append('span').classed('thesis',true).text(function (d) {return d.thesis.short;}).attr('title', function (d) {return d.thesis.long;});
                li.append('span').classed('party1',true).text(function (d) {return util.showSign(d.parties[1].answer);});
            }

            base.each(function(data) {
                var li = base.selectAll('li').data(data, function(d) {return d.thesis.short;});
                li.call(updateItem);
                li.enter().append('li').call(updateItem);
                li.exit().remove();
            });
        }


        this.theses.forEach(function (d,i) {
            var answer0 = this.answers[party0Index][i],
                answer1 = this.answers[party1Index][i],
                result = {
                    thesis:d,
                    matching: answer0 * answer1 === 1,
                    nonMatching: answer0 * answer1 === -1,
                    parties: [
                        {answer:answer0,name:party0.long},
                        {answer:answer1,name:party1.long}
                    ]
                }
            ;
            if (result.matching) {
                matchingTheses.push(result);
            } else if (result.nonMatching) {
                nonMatchingTheses.push(result);
            }
        }, this);

        this.matchList.data([matchingTheses]).call(updateCloud);
        this.nonMatchList.data([nonMatchingTheses]).call(updateCloud);
    }

};
