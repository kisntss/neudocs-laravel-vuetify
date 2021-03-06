<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 13:36
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Relational\RequestPart;

/**
 * Class RequestPartsCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class RequestPartsCollection extends Collection implements NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType(): string {
        RequestPart::class;
    }

}