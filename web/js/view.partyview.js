var partyView = {

    translations : {
        '+':'befürwortet',
        '-':'abgelehnt',
        '~':'weder befürwortet noch abgelehnt'
    },

    init:function() {
        partyMatrix.init(
            d3.select('#parties'),
            data.tdm.values,
            data.tdm.documents,
            data.tdm.terms,
            data.reasonMatrix.values
        );

        floatBox.init(d3.select('#parties'));

        partyMatrix.dispatcher.on('selected', function(i,j) {
            var text = d3.select(this).select('span').text();
            floatBox.set(
                this.offsetLeft,
                this.offsetTop,
                '<strong>'+data.tdm.terms[i].long+'</strong>',
                'wird von <strong>'+data.tdm.documents[j].long + '</strong> ' + partyView.translations[text],
                data.reasonMatrix.values[i][j][0]
            )
        });

        partyMatrix.dispatcher.on('unselected', function(i,j) {
            floatBox.blur();
        });
    }
}

jQuery(partyView.init);



