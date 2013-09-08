all: bundestag europaparlament landtag

bundestag: bundestagswahl2013 bundestagswahl2009 bundestagswahl2005

bundestagswahl2013:
	cd bundestagswahl2013 &&  $(MAKE)

bundestagswahl2009:
	cd bundestagswahl2009 &&  $(MAKE)

bundestagswahl2005:
	cd bundestagswahl2005 &&  $(MAKE)

europaparlament: europawahl2009 europa2004

europawahl2009:
	cd europawahl2009 &&  $(MAKE)
europa2004:
	cd europa2004 &&  $(MAKE)


landtag: bayern2013 niedersachsen2013 nrw2012 schleswigholstein2012 saarland2012 berlin2011 bremen2011 bw2011 rlp2011 hamburg2011 nrw2010 hamburg2008 niedersachsen2008 bremen2007 berlin2006 bw2006 rlp2006 sachsenanhalt2006 nrw2005 schleswigholstein2005 sachsen2004 bayern2003

bayern2013:
	cd bayern2013 && $(MAKE)

niedersachsen2013:
	cd niedersachsen2013 &&  $(MAKE)

nrw2012:
	cd nrw2012 &&  $(MAKE)

schleswigholstein2012:
	cd schleswigholstein2012 &&  $(MAKE)

saarland2012:
	cd saarland2012 &&  $(MAKE)

berlin2011:
	cd berlin2011 &&  $(MAKE)

bremen2011:
	cd bremen2011 &&  $(MAKE)

bw2011:
	cd bw2011 &&  $(MAKE)

rlp2011:
	cd rlp2011 &&  $(MAKE)

hamburg2011:
	cd hamburg2011 &&  $(MAKE)

nrw2010:
	cd nrw2010 &&  $(MAKE)

hamburg2008:
	cd hamburg2008 &&  $(MAKE)

niedersachsen2008:
	cd niedersachsen2008 &&  $(MAKE)

bremen2007:
	cd bremen2007 &&  $(MAKE)

berlin2006:
	cd berlin2006 &&  $(MAKE)

bw2006:
	cd bw2006 &&  $(MAKE)

rlp2006:
	cd rlp2006 &&  $(MAKE)

sachsenanhalt2006:
	cd sachsenanhalt2006 &&  $(MAKE)

nrw2005:
	cd nrw2005 &&  $(MAKE)

schleswigholstein2005:
	cd schleswigholstein2005 &&  $(MAKE)

sachsen2004:
	cd sachsen2004 &&  $(MAKE)

bayern2003:
	cd bayern2003 &&  $(MAKE)


clean:
	rm */data.js
	rm -Rf */tmp