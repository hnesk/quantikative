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

    const DATA_TYPE_VALUE = 0;
    const DATA_TYPE_REASON = 1;


    /**
     * @param string $dataSet
     * @param int $langId (0=german / 1=english)
     * @param int $dataType
     * @throws \InvalidArgumentException
     * @return TermDocumentMatrix
     */
    public function load($dataSet, $langId = 0, $dataType = self::DATA_TYPE_VALUE);

    /**
     * @return array<string>
     */
    public function getDataSets();
}