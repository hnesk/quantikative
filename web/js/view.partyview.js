var partyView = {
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
            floatBox.set(
                this.offsetLeft,
                this.offsetTop,
                '<strong>'+data.tdm.terms[i].long+'</strong>',
                'wird von <strong>'+data.tdm.documents[j].long + '</strong> ' + (this.textContent == '+' ? 'befürwortet' : 'abgelehnt'),
                data.reasonMatrix.values[i][j][0]
            )
        });

        partyMatrix.dispatcher.on('unselected', function(i,j) {
            floatBox.blur();
        });
    }
}

jQuery(partyView.init);



