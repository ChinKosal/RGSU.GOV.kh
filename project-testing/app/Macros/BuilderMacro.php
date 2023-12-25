<?php

namespace App\Macros;

use Illuminate\Database\Query\Builder;

class BuilderMacro
{
    protected $builder;
    public function __construct(Builder $builder = null)
    {
        // if (!$builder) {
        //     return array(
        //         ['whereLike', ['field', 'value']],
        //         ['trash', ['value']],
        //         ['cud', ['values', 'uniquesBy', 'fields']],
        //     );
        // }
        $this->builder = $builder;
    }

    public function whereLike($field, $value)
    {
        if (empty($value)) return $this->builder;
        return $this->builder->where($field, 'like', "%{$value}%");
    }

    public function trash($value)
    {
        if ($value) {
            return $this->builder->onlyTrashed();
        }
        return $this->builder->withTrashed();
    }

    /**
     * bulk Create, Update, Delete records in one query
     *  ~ delete will compare field only first in uniqueBy
     * @param array $values as array data
     * @param array $uniqueBy field to check if record exist
     * @param array $fields field to update
     */
    public function insertUpdateDelete(array $values, array $uniqueBy, array $fields)
    {
        //insert or update
        $this->builder->upsert(
            $values,
            $uniqueBy,
            $fields
        );
        //delete
        $this->builder->whereNotIn($uniqueBy[0], collect($values)->pluck($uniqueBy[0]))->delete();
    }
}
