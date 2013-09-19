
var floatBox = {
    root:null,
    box: null,
    head1: null,
    head2: null,
    content: null,

    init : function (rootSelection) {
        this.root = rootSelection;
        this.box = this.root.append("div").attr('class', 'popover party-popover clearfix');
        var headrow = this.box.append('div');
        headrow.append('button')
            .attr({type:'button','class':'pull-right close'})
            .html('&times;')
            .on('click', this.hide.bind(this));
        this.head1 = headrow.append("h4").attr('class','popover-title');


        this.head2 = this.box.append("h4").attr('class','popover-title');
        this.content = this.box.append('div').attr('class','popover-content');
    },


    set: function (x, y, head1, head2, content) {
        var newX = 0,
            newY = 0,
            myWidth = 0,
            myHeight = 0,
            newHeight = 0,
            oldHeight = 0
        ;
        this.head1.html(d3.functor(head1));
        this.head2.html(d3.functor(head2));

        oldHeight = this.box.style({height:'auto', overflow:'auto', display:'block'}).property('offsetHeight');
        this.content.html(d3.functor(content));
        myHeight = this.box.property('offsetHeight');
        myWidth = this.box.property('offsetWidth');

        newX = x + myWidth > this.root.property('offsetWidth') && x - myWidth > 0 ? x - myWidth - 0 : x + 35;
        newY = y + myHeight + 35 > Math.min(this.root.property('offsetHeight'), window.innerHeight) && y - myHeight > 35 ? y - myHeight : y + 35

        this.box.style({height:oldHeight+'px',overflow:'hidden'}).transition().duration(500).style({'left':newX+'px','top':newY+'px','height':myHeight+'px',opacity:0.95});
    },

    hide: function() {
        this.box.transition().style({opacity:0,display:'none'});
    },

    blur: function () {
        this.box.transition().style({opacity:0.90});
    }
};