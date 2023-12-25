<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class QuizResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id'  => $item->id,
                'name' => json_decode($item->title)->{app()->getLocale()},
                'point' => $item->point,
                'duration' => $item->duration,
                'can_skip' => $item->can_skip,
                'order' => $item->order,
                'allow_multiple_choose' => $item->allow_multiple_choose,
                'answer' => $item->quizOptions->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => json_decode($item->title)->{app()->getLocale()},
                        'is_true' => $item->is_true,
                        'point' => $item->point,
                        'order' => $item->order,
                    ];
                }),
            ];
        });
    }
}
