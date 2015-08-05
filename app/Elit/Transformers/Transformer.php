<?php 

namespace Elit\Transformers;

abstract class Transformer
{
    
    /**
     * Customize the names of fields in a Lesson after we've
     * retrieved it from the db.
     *
     * @return array
     * @author PJ
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    
    public abstract function transform($item);
}
