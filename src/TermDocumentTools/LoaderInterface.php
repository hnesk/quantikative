<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 20:14
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;

use TermDocumentTools\TermDocumentMatrix;

interface LoaderInterface {

    /**
     * @param string $dataSet
     * @param int $langId
     * @return TermDocumentMatrix
     */
    public function load($dataSet, $langId = 0);

    /**
     * @return array<string>
     */
    public function getDataSets();
}