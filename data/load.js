var fs = require('fs'),
    vm = require('vm'),
    util = require('util')
;


function getData(path) {
    var code = fs.readFileSync(path).toString(),
        sandbox = {};
    vm.runInNewContext(code, sandbox, path);
    return sandbox;
}

var filename = process.argv[2],
    data = getData(filename),
    out = {
        parties: data.WOMT_aParteien,
        theses: data.WOMT_aThesen,
        matrix: data.WOMT_aThesenParteien
    };
//process.stdout.write(util.inspect(out, {depth:null}));
process.stdout.write(util.format("%j", out));

