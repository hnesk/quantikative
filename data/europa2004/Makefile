data.js: tmp/module_definition.js
	node ../load.js tmp/module_definition.js > data.js

tmp/module_definition.js: tmp/wahlomat.zip
	unzip -p tmp/wahlomat.zip *module_definition*.js | iconv -f utf-8 -t utf8 -c -s > tmp/module_definition.js

tmp/wahlomat.zip: setup
	curl -L -o tmp/wahlomat.zip 'http://www.wahl-o-mat.de/europa2004/wahlomat.zip'

setup:
	mkdir -p tmp

clean:
	rm -Rf tmp