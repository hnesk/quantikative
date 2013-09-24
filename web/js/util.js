var util = {
    identity : function(d) {
        return d;
    },
    access : function (key) {
        return function(d) {
            return d[key];
        }
    },
    accessFloat : function (key) {
        return function(d) {
            return 1.0*d[key];
        }
    },

    compose: function (func1, func2) {
        return function() {
            return func1(func2.apply(null, arguments));
        };
    },

    repeat : function (text,n) {
        return new Array(1 + parseInt(n)).join(text);
    },

    showSign : function (d) {
        return (d > 0 ? '+' : (d < 0 ? '-' : '~'));
    }
};
