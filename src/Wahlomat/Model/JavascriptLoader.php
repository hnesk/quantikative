<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 20:14
 * To change this template use File | Settings | File Templates.
 */

namespace Wahlomat\Model;

use LinearAlgebra\Matrix;
use TermDocumentTools\Document;
use TermDocumentTools\Documents;
use TermDocumentTools\Term;
use TermDocumentTools\Terms;
use TermDocumentTools\TermDocumentMatrix;
use TermDocumentTools\LoaderInterface;

class JavascriptLoader implements LoaderInterface {

    protected $baseDirectory;

    protected static $SPECIAL_CHARS = array(
        "[BSLZ]" => '',
        "[LZ]" => '',
        "~@-@~" => "'",
        "- " => ""
    );

    protected static $SHORTEN_PARTY= array(
        'DIE' => '',
        'TIERSCHUTZPARTEI' => 'MUT',
        '/FREIE WÄHLER' => '',
        'NRW' => '',
        'FREIE WÄHLER' => 'FREI',
        'PARTEI DER VERNUNFT' => 'VERNUNFT',
        'PRO DEUTSCHLAND' => 'PRO',
        'BÜNDNIS 21' => 'B21',
        'PARTEI DER' => '',
        'CDU / CSU' => 'CDU/CSU',
    );

    protected static $SHORTEN_THESIS= array(
        'Mindestlohn' => 'Einführung Mindestlohn',
        'Tempolimit' => 'Tempolimit auf Autobahnen',
        'Eurobonds' => '€-Staaten haften für eigene Schulden',
        'Euro' => 'Euro beibehalten',
        'Strompreis' => 'Regulierung Strompreis',
        'Videoüberwachung' => 'Ausbau Videoüberwachung',
        'Grundeinkommen' => 'Grundeinkommen einführen',
        'Getrennter Schulunterricht' => 'Gemeinsamer Unterricht',
        'Spitzensteuersatz' => 'Spitzensteuersatz erhöhen',
        'Kohlekraft' => 'Kein Neubau Kohlekraftwerke',
        'Pille danach' => 'Pille danach rezeptpflichtig',
        'Flüchtlingspolitik' => 'Mehr Flüchtlinge aufnehmen',
        'Staatliche Unterstützung von Studierenden' => 'Elternunabhängiges BAföG',
        'Grenzkontrollen' => 'Einreisekontrollen',
        'Länderfinanzausgleich' => 'Weniger Länderfinanzausgleich',
        'Rente mit 67' => 'Renteneintrittsalter senken',
        'Rüstungsexporte' => 'Rüstungsexporte verbieten',
        'Einkommensteuer' => 'Ehegattensplitting beibehalten',
        'Nebeneinkünfte' => 'MdB - Nebeneinkünfte offenlegen',
        'EEG-Umlage' => 'keine Subvention energieintensiver Unternehmen',
        'Vorratsdatenspeicherung' => 'keine Vorratsdatenspeicherung',
        'Doppelte Staatsangehörigkeit' => 'keine doppelte Staatsangehörigkeit'

    );


    public function __construct($baseDirectory = '') {
        $this->baseDirectory = $baseDirectory;
    }

    public function getDataSets() {
        $dataSets = array();
        $it = new \GlobIterator($this->baseDirectory.'*/data.js');
        foreach ($it as $file) {
            /* @var $file \SplFileInfo */
            $path = $file->getPath();
            $dataSet = str_replace($this->baseDirectory, '', $path);
            $dataSets[$dataSet] = $dataSet;
        }
        return $dataSets;
    }


    /**
     * @param string $dataSet
     * @param int $langId (0=german / 1=english)
     * @param int $dataType
     * @throws \InvalidArgumentException
     * @return TermDocumentMatrix
     */
    public function load($dataSet, $langId = 0, $dataType = self::DATA_TYPE_VALUE) {
        $file = $this->baseDirectory.$dataSet.'/data.js';
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf("File '%s' for data set '%s' not found.",$file, $dataSet));
        }


        $data = json_decode(file_get_contents($file));

        $terms = new Terms();

        foreach ($data->theses as $i=>$thesis) {
            $terms->append(new Term($i, self::shortenThesis(self::fixString($thesis[$langId][0])), self::fixString($thesis[$langId][1])));
        }

        $documents = new Documents();
        foreach ($data->parties as $i => $party) {
            $documents->append(new Document($i, self::shortenParty($party[$langId][1]), self::fixString($party[$langId][0])));
        }

        if ($dataType === self::DATA_TYPE_VALUE) {
            $matrix = Matrix::create($data->matrix, true)->transpose();
        } else if ($dataType === self::DATA_TYPE_REASON) {
            $matrix = Matrix::create($data->reason, false)->transpose();
        }

        return new TermDocumentMatrix($terms, $documents, $matrix);
    }

    /**
     * @param string $s
     * @return string
     */
    public static function fixString($s) {
        return trim(str_replace(array_keys(self::$SPECIAL_CHARS), array_values(self::$SPECIAL_CHARS), $s));
    }


    /**
     * @param string $party
     * @return string
     */
    public static function shortenParty($party) {
        return trim(str_replace(array_keys(self::$SHORTEN_PARTY), array_values(self::$SHORTEN_PARTY), mb_strtoupper($party,'utf8')));
    }

    /**
     * @param string $party
     * @return string
     */
    public static function shortenThesis($thesis) {
        return str_replace(array_keys(self::$SHORTEN_THESIS), array_values(self::$SHORTEN_THESIS), $thesis);
    }

}