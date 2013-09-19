var util = {
    identity : function(d) {
        return d;
    },
    access : function (key) {
        return function(d) {
            return d[key];
        }
    },
    repeat : function (text,n) {
        return new Array(1 + parseInt(n)).join(text);
    },

    showSign : function (d) {
        return (d > 0 ? '+' : (d < 0 ? '-' : '~'));
    }
};
