<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;

class StoreService
{
    /**
     * save data to database
     * @param Builder $model
     * @param array $field
     * @return array
     */
    public static function save($model, array $data, $message)
    {
        try {
            $model->fill($data);
            $model->save();
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.create.success"),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.create.error"),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param Builder $model
     * @param array $field
     * @return array
     */
    public static function update($model, array $data, $message)
    {
        try {
            $model->update($data);
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.update.success"),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.update.error"),
                'error' => true,
            ]);
        }
    }

    /**
     * @param Builder $model
     * @param string $message
     */
    public static function moveToTrash($model, $message)
    {
        try {
            $model->delete();
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.delete.success"),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.delete.error"),
                'error' => true,
            ]);
        }
    }

    /**
     * @param Builder $model
     * @param string $message
     */
    public static function restore($model, $message)
    {
        try {
            $model->onlyTrashed()->restore();
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.restore.success"),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.restore.error"),
                'error' => true,
            ]);
        }
    }

    /**
     * @param Builder $model
     * @param string $message
     */
    public static function delete($model, $message)
    {
        try {
            $model
                ->withTrashed()
                ->forceDelete();
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.delete.success"),
                'error' => false,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => __("$message.message.delete.error"),
                'error' => true,
            ]);
        }
    }
}
