
var floatBox = {
    root:null,
    box: null,
    head1: null,
    head2: null,
    content: null,

    init : function (rootSelection) {
        this.root = rootSelection;
        this.box = this.root.append("div").attr('class', 'popover party-popover');
        this.head1 = this.box.append("h3").attr('class','popover-title');
        this.head2 = this.box.append("h3").attr('class','popover-title');
        this.content = this.box.append('div').attr('class','popover-content');
    },


    set: function (x, y, head1, head2, content) {

        var newX = 0,
            newY = 0,
            myWidth = 0,
            myHeight = 0
            ;
        this.head1.html(head1);
        this.head2.html(head2);
        this.content.html(content);

        myWidth = this.box.property('offsetWidth');
        myHeight = this.box.property('offsetHeight');

        newX = x + myWidth > this.root.property('offsetWidth') ? x - myWidth - 0 : x + 35;
        newY = y + myHeight + 35 > Math.min(this.root.property('offsetHeight'), window.innerHeight) && y - myHeight > window.scrollY ? y - myHeight + 35 : y + 70

        this.box.style('opacity',0.8).transition().duration(500).style({'left':newX+'px','top':newY+'px',opacity:0.95});
    },


    blur: function () {
        this.box.transition().duration(500).style({opacity:0.90});
    }
};