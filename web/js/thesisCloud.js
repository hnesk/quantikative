
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


    select: function (party0Index, party0,party1Index, party1) {
        var matchingTheses = [],
            nonMatchingTheses = []
        ;


        function updateCloud(base, data) {
            var li = base.selectAll('li').data(data)
                .text(function (d) {return d.thesis.short;})
                .attr('title', function (d) {return d.thesis.long;});

            li.enter()
                .append('li')
                .text(function (d) {return d.thesis.short;})
                .attr('title', function (d) {return d.thesis.long;});

            li.exit().remove();
        }



        this.theses.forEach(function (d,i) {
            var answer0 = this.answers[party0Index][i],
                answer1 = this.answers[party1Index][i],
                result = {
                    thesis:d,
                    matching: answer0 === answer1,
                    nonMatching: answer0 * answer1 === -1,
                    parties: [
                        {answer:answer0,name:party0.short},
                        {answer:answer1,name:party1.short},
                    ]
                }
            ;
            if (result.matching) {
                matchingTheses.push(result);
            } else if (result.nonMatching) {
                nonMatchingTheses.push(result);
            }
        }, this);


        updateCloud(this.matchList, matchingTheses);
        updateCloud(this.nonMatchList, nonMatchingTheses);

    }

};
