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
        'PARTEI DER VERNUNFT' => 'PDV'
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
     * @throws \InvalidArgumentException
     * @return TermDocumentMatrix
     */
    public function load($dataSet, $langId = 0) {
        $file = $this->baseDirectory.$dataSet.'/data.js';
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf("File '%s' for data set '%s' not found.",$file, $dataSet));
        }


        $data = json_decode(file_get_contents($file));

        $terms = new Terms();

        foreach ($data->theses as $i=>$thesis) {
            $terms->append(new Term($i, self::fixString($thesis[$langId][0]), self::fixString($thesis[$langId][1])));
        }

        $documents = new Documents();
        foreach ($data->parties as $i => $party) {
            $documents->append(new Document($i, self::shortenParty($party[$langId][1]), self::fixString($party[$langId][0])));
        }

        return new TermDocumentMatrix($terms, $documents, Matrix::create($data->matrix)->transpose());
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
}