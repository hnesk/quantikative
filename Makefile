all: bundestag europaparlament landtag

bundestag: bundestagswahl2009 bundestagswahl2005

bundestagswahl2009:
	cd data/bundestagswahl2009 &&  $(MAKE)

bundestagswahl2005:
	cd data/bundestagswahl2005 &&  $(MAKE)

europaparlament: europawahl2009 europa2004

europawahl2009:
	cd data/europawahl2009 &&  $(MAKE)
europa2004:
	cd data/europa2004 &&  $(MAKE)


landtag: nrw2012 niedersachsen2013 schleswigholstein2012 saarland2012 berlin2011 bremen2011 bw2011 rlp2011 hamburg2011 nrw2010 hamburg2008 niedersachsen2008 bremen2007 berlin2006 bw2006 rlp2006 sachsenanhalt2006 nrw2005 schleswigholstein2005 sachsen2004 bayern2003


nrw2012:
	cd data/nrw2012 &&  $(MAKE)

niedersachsen2013:
	cd data/niedersachsen2013 &&  $(MAKE)

schleswigholstein2012:
	cd data/schleswigholstein2012 &&  $(MAKE)

saarland2012:
	cd data/saarland2012 &&  $(MAKE)

berlin2011:
	cd data/berlin2011 &&  $(MAKE)

bremen2011:
	cd data/bremen2011 &&  $(MAKE)

bw2011:
	cd data/bw2011 &&  $(MAKE)

rlp2011:
	cd data/rlp2011 &&  $(MAKE)

hamburg2011:
	cd data/hamburg2011 &&  $(MAKE)

nrw2010:
	cd data/nrw2010 &&  $(MAKE)

hamburg2008:
	cd data/hamburg2008 &&  $(MAKE)

niedersachsen2008:
	cd data/niedersachsen2008 &&  $(MAKE)

bremen2007:
	cd data/bremen2007 &&  $(MAKE)

berlin2006:
	cd data/berlin2006 &&  $(MAKE)

bw2006:
	cd data/bw2006 &&  $(MAKE)

rlp2006:
	cd data/rlp2006 &&  $(MAKE)

sachsenanhalt2006:
	cd data/sachsenanhalt2006 &&  $(MAKE)

nrw2005:
	cd data/nrw2005 &&  $(MAKE)

schleswigholstein2005:
	cd data/schleswigholstein2005 &&  $(MAKE)

sachsen2004:
	cd data/sachsen2004 &&  $(MAKE)

bayern2003:
	cd data/bayern2003 &&  $(MAKE)

