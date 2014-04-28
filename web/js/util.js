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
    },
    key: function (d) {
        return d.key;
    },
    value: function (d) {
        return d.value;
    },

    highlight : function(highlight, what) {
        what = what || 'fill';
        baseColor = '';
        return {
            on: function(d,i) {
                baseColor = d3.select(this).style.color;
                return d3.select(this).transition().style(what, highlight);
            },
            off: function(d,i) {
                return d3.select(this).transition().style(what, baseColor);
            },
            toggle: function(v) {
                return d3.select(this).transition().style(what, v ? highlight : baseColor);
            }
        };
    }


};
