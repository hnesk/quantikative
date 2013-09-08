var util = {
    identity : function(d) {
        return d;
    },
    access : function (key) {
        return function(d) {
            return d[key];
        }
    }
}
