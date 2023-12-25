<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class QueryService
{
    /**
     * @var Builder
     * @return Builder
     */
    public function betweenDate($model, $fallbackCurrentDate = false)
    {
        return $model->when(filled(request('from_date')), function ($query) {
            $query->whereDate("created_at", ">=", date("Y-m-d", strtotime(request('from_date'))));
        })
            ->when(filled(request('to_date')), function ($query) {
                $query->whereDate("created_at", "<=", date("Y-m-d", strtotime(request('to_date'))));
            })
            ->when(!filled(request('to_date')) && !filled(request('to_date')), function ($query) use ($fallbackCurrentDate) {
                $query->when($fallbackCurrentDate, function ($query) {
                    $query->whereDate("created_at", ">=", date("Y-m-d", strtotime(Carbon::now())));
                })
                    ->whereDate("created_at", "<=", date("Y-m-d", strtotime(Carbon::now())));
            });
    }

    /**
     * @param $default
     * @return string
     */
    public function orderBy($default = "created_at:desc")
    {
        $orderBy = mb_split(':', request('sort_by') ?? $default);
        return "$orderBy[0] $orderBy[1]";
    }

    /**
     * @param $model Builder
     * @param $field
     * @param bool $fallbackCurrentDate
     * @return Builder
     */
    public function customBetweenDate($model, $field, $fallbackCurrentDate = false)
    {
        return $model->when(filled(request('from_date')), function ($query) use ($field) {
            $query->whereDate($field, ">=", date("Y-m-d", strtotime(request('from_date'))));
        })
            ->when(filled(request('to_date')), function ($query)  use ($field) {
                $query->whereDate($field, "<=", date("Y-m-d", strtotime(request('to_date'))));
            })
            ->when(!filled(request('to_date')) && !filled(request('to_date')), function ($query) use ($fallbackCurrentDate, $field) {
                $query->when($fallbackCurrentDate, function ($query) use ($field) {
                    $query->whereDate($field, ">=", date("Y-m-d", strtotime(Carbon::now())));
                })
                    ->whereDate($field, "<=", date("Y-m-d", strtotime(Carbon::now())));
            });
    }

    /**
     * @param $model Builder
     * @param $status
     * @param $field
     * @param array $selectColumns
     * @return Builder
     */
    // get single record
    public function queryCollectionWithSingleRecord($model, $status, $field, $selectColumns = [])
    {
        return $model->whereStatus($status)
                ->when(filled($field), function ($query) use ($field) {
                    $field = mb_split(':', $field);
                    $query->where($field[0], $field[1]);
                })
                ->when(filled($selectColumns), function ($query) use ($selectColumns) {
                    $query->select($selectColumns);
                })
                ->first();
    }

    /**
     * @param $model Builder
     * @param $status
     * @param $default
     * @return Builder
     */
    // get multiple records
    public function queryCollectionWithMultipleRecords($model, $status, $default = "id:desc", $selectColumns = [])
    {
        return $model->whereStatus($status)
                ->when(filled($selectColumns), function ($query) use ($selectColumns) {
                    $query->select($selectColumns);
                })
                ->orderByRaw($this->orderBy($default));
    }

    /**
     * @param $model Builder
     * @param $slug
     * @param $id
     * @return Builder
     */
    // unique slug
    public function uniqueSlug($model, $slug, $id = null)
    {
        $query = $model->whereSlug($slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        return $query->exists() ? $slug . '-' . uniqid() : $slug;
    }
}
