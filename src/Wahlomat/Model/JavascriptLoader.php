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
use TermDocumentTools\TermDocumentMatrix;
use TermDocumentTools\Terms;

class JavascriptLoader {

    protected static $SPECIAL_CHARS = array(
        "[BSLZ]" => '',
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


    /**
     * @param string $file
     * @param int $langId (0=german / 1=english)
     * @return TermDocumentMatrix
     */
    public static function load($file, $langId = 0) {

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