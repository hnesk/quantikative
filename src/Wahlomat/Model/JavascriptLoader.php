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
    /**
     * @param string $file
     * @return TermDocumentMatrix
     */
    public static function load($file) {

        $theses = array();
        $parties = array();
        $matrix = array();

        $fixString = function ($s) { return trim(str_replace('[BSLZ]','',utf8_encode($s)));};

        $lines = explode("\n", file_get_contents($file));

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match("#^WOMT_aThesen\[(\d+)\]\[0\]\[0\]='([^']+)';#",$line, $matches)) {
                $theses[intval($matches[1])]['short'] = $fixString($matches[2]);
                $theses[intval($matches[1])]['index'] = intval($matches[1]);
            }
            if (preg_match("#^WOMT_aThesen\[(\d+)\]\[0\]\[1\]='([^']+)';#",$line, $matches)) {
                $theses[intval($matches[1])]['long'] = $fixString($matches[2]);
            }

            if (preg_match("#^WOMT_aParteien\[(\d+)\]\[0\]\[1\]='([^']+)';#",$line, $matches)) {
                $parties[intval($matches[1])]['short'] = $fixString($matches[2]);
                $parties[intval($matches[1])]['index'] = intval($matches[1]);
            }

            if (preg_match("#^WOMT_aParteien\[(\d+)\]\[0\]\[0\]='([^']+)';#",$line, $matches)) {
                $parties[intval($matches[1])]['long'] = $fixString($matches[2]);
            }

            if (preg_match("#^WOMT_aThesenParteien\[(\d+)\]\[(\d+)\]='([+-]?[01])';#",$line, $matches)) {
                $matrix[intval($matches[2])][intval($matches[1])] = intval($matches[3]);
            }
        }

        $replacements = array(
            'DIE' => '',
            'TIERSCHUTZPARTEI' => 'MUT',
            '/FREIE WÄHLER' => '',
            'NRW' => '',
            'FREIE WÄHLER' => 'FREI',
            'PARTEI DER VERNUNFT' => 'PDV'
        );

        foreach ($parties as $k=>$p) {
            $parties[$k]['shortest'] = trim(str_replace(array_keys($replacements), array_values($replacements), mb_strtoupper($parties[$k]['short'],'utf8')));
        }

        $terms = new Terms();
        foreach ($theses as $i => $thesis) {
            $terms->append(new Term($i, $thesis['short'], $thesis['long']));
        }

        $documents = new Documents();
        foreach ($parties as $i => $party) {
            $documents->append(new Document($i, $party['shortest'], $party['long']));
        }


        return new TermDocumentMatrix($terms, $documents, new Matrix($matrix));
    }

}