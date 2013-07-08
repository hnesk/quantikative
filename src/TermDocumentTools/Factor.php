<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;


class Factor implements \JsonSerializable {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     */
    public function __construct($id, $name, $description ='')
    {
        $this->id = intval($id);
        $this->name = trim($name);
        $this->description = trim($description);
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    public function __toString() {
        return $this->name;
    }


    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return (object) array(
            'index' => $this->id,
            'short' => $this->name,
            'long' => $this->description,
        );
    }
}