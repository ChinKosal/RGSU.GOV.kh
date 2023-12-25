<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class NewsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id,
                    'category' => [
                        'id' => $item->category->id,
                        'name' => json_decode($item->category->name)->{app()->getLocale()},
                    ],
                    'thumbnail' => asset('file_manager' . $item->thumbnail),
                    'title' => json_decode($item->title)->{app()->getLocale()},
                    'sort_content' => Str::limit(strip_tags(json_decode($item->content)->{app()->getLocale()}), 50),
                    'created_date' => $item->created_at,
                ];
            }),
            'paginate' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
            ],
        ];
    }
}
