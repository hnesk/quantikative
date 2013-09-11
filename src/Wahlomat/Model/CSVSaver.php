<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 20:14
 * To change this template use File | Settings | File Templates.
 */

namespace Wahlomat\Model;

use TermDocumentTools\TermDocumentMatrix;
use TermDocumentTools\LoaderInterface;

class CSVSaver implements LoaderInterface {

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
    );


    /**
     * @param \TermDocumentTools\TermDocumentMatrix $tdm
     * @return string
     */
    public function save(TermDocumentMatrix $tdm) {

    }


}