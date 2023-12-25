<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Career;
use App\Models\Category;
use App\Models\Committee;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\GfatmGrant;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Principal;
use App\Models\Slider;
use App\Models\SocialMedia;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class WebsiteService
{
    private $service, $active, $inactive;
    public function __construct()
    {
        $this->service  = new QueryService();
        $this->active   = config('dummy.status.active.key');
        $this->inactive = config('dummy.status.disabled.key');
    }

    public function queryCategoryMultipleRecords($type = null)
    {
        Log::info("Start WebsiteService > queryCategoryMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Category::query(), $this->active, "ordering:asc")
                        ->whereNull('parent_id')
                        ->when($type, function ($query) use ($type) {
                            $query->whereType($type);
                        })
                        ->get();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCategoryMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryCategorySingleRecord($slug)
    {
        Log::info("Start WebsiteService > queryCategorySingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(Category::query(), $this->active, "slug:$slug", ['name','id']);
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCategorySingleRecord | payload: " . $e->getMessage());
        }
    }

    public function querySliderMultipleRecords()
    {
        Log::info("Start WebsiteService > querySliderMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Slider::query(), $this->active, "ordering:asc")
            ->get();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > querySliderMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryCommitteeMultipleRecords($category = null, $type = null, $perPage = null)
    {
        Log::info("Start WebsiteService > queryCommitteeMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Committee::query(), $this->active, "id:desc")
                        ->when($category, function ($query) use ($category) {
                            // use slug category to get id with function query category
                            $category = $this->queryCategorySingleRecord($category)?->id;

                            // use id category to get document
                            $query->whereJsonContains('category', $category);
                        })
                        ->when($type, function ($query) use ($type) {
                            $query->whereType($type);
                        })
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCommitteeMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryCommitteeSingleRecords($category, $type)
    {
        Log::info("Start WebsiteService > queryCommitteeSingleRecords | payload: ");
        try {
            $category = $this->queryCategorySingleRecord($category)?->id;
            return Committee::query()
                        ->whereStatus($this->active)
                        ->whereType($type)
                        ->whereJsonContains('category', $category)
                        ->first();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCommitteeSingleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryDocumentMultipleRecords($category = null, $type = null, $year = null, $perPage = null, $selectColumns = [])
    {
        Log::info("Start WebsiteService > queryDocumentMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Document::query(), $this->active,"id:desc", $selectColumns)
                        ->when($category, function ($query) use ($category) {
                            // use slug category to get id with function query category
                            $category = $this->queryCategorySingleRecord($category)?->id;

                            // use id category to get document
                            $query->whereJsonContains('category', $category);
                        })
                        ->when($type, function ($query) use ($type) {
                            $query->whereType($type);
                        })
                        ->when($year, function ($query) use ($year) {
                            $query->where('year', $year);
                        })
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryDocumentMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryOnlyYearDocumentMultipleRecords($category, $type)
    {
        Log::info("Start WebsiteService > queryOnlyYearDocumentMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Document::query(), $this->active, "year:desc")
                        ->when($category, function ($query) use ($category) {
                            // use slug category to get id with function query category
                            $category = $this->queryCategorySingleRecord($category)?->id;

                            // use id category to get document
                            $query->whereJsonContains('category', $category);
                        })
                        ->when($type, function ($query) use ($type) {
                            $query->whereType($type);
                        })
                        ->select('year')
                        ->distinct()
                        ->get();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryOnlyYearDocumentMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryGfatmGrantMultipleRecords($category = null, $perPage = null)
    {
        Log::info("Start WebsiteService > queryGfatmGrantMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(GfatmGrant::query(), $this->active, "id:desc")
                        ->when($category, function ($query) use ($category) {
                            // use slug category to get id with function query category
                            $category = $this->queryCategorySingleRecord($category)?->id;

                            // use id category to get document
                            $query->whereJsonContains('category', $category);
                        })
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryGfatmGrantMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryPrincipalMultipleRecords($category = null, $type = null, $perPage = null)
    {
        Log::info("Start WebsiteService > queryPrincipalMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Principal::query(), $this->active, "id:desc")
                        ->when($category, function ($query) use ($category) {
                            // use slug category to get id with function query category
                            $category = $this->queryCategorySingleRecord($category)?->id;

                            // use id category to get principal
                            $query->whereJsonContains('category', $category);
                        })
                        ->when($type, function ($query) use ($type) {
                            $query->whereType($type);
                        })
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryPrincipalMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryPrincipalSingleRecord($category, $type)
    {
        Log::info("Start WebsiteService > queryPrincipalSingleRecord | payload: ");
        try {
            $category = $this->queryCategorySingleRecord($category)?->id;
            return Principal::query()
                        ->whereStatus($this->active)
                        ->whereType($type)
                        ->whereJsonContains('category', $category)
                        ->first();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryPrincipalSingleRecord | payload: " . $e->getMessage());
        }
    }

    public function queryNewsMultipleRecords($notInIds = [], $perPage = 10)
    {
        Log::info("Start WebsiteService > queryNewsMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(News::query(), $this->active, "id:desc")
                ->when(request()->has('search'), function ($query) {
                    $columns = ['title->km', 'title->en', 'content->km', 'content->en'];
                    $query->where(function ($query) use ($columns) {
                        foreach ($columns as $column) {
                            $query->orWhere($column, 'like', '%' . request('search') . '%');
                        }
                    });
                })
                ->when($notInIds, function ($query) use ($notInIds) {
                    $query->whereNotIn('id', $notInIds);
                })
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryNewsMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryNewsSingleRecord($slug)
    {
        Log::info("Start WebsiteService > queryNewsSingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(News::query(), $this->active,"slug:$slug");
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryNewsSingleRecord | message: ". $e->getMessage());
        }
    }

    public function queryActivityMultipleRecords($notInIds = [], $perPage = null)
    {
        Log::info("Start WebsiteService > queryActivityMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Activity::query(), $this->active, "id:desc")
                        ->when(request()->has('search'), function ($query) {
                            $columns = ['title->km', 'title->en', 'content->km', 'content->en'];
                            $query->where(function ($query) use ($columns) {
                                foreach ($columns as $column) {
                                    $query->orWhere($column, 'like', '%' . request('search') . '%');
                                }
                            });
                        })
                        ->when($notInIds, function ($query) use ($notInIds) {
                            $query->whereNotIn('id', $notInIds);
                        })
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryActivityMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryActivitySingleRecord($slug)
    {
        Log::info("Start WebsiteService > queryActivitySingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(Activity::query(), $this->active,"slug:$slug");
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryActivitySingleRecord | message: ". $e->getMessage());
        }
    }

    public function queryGallerySingleRecord()
    {
        Log::info("Start WebsiteService > queryGallerySingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(Gallery::query(), $this->active, null);
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryGallerySingleRecord | message: ". $e->getMessage());
        }
    }


    public function queryVideoMultipleRecords($perPage = null)
    {
        Log::info("Start WebsiteService > queryVideoMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Video::query(), $this->active, "id:desc")
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryVideoMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryCareerMultipleRecords($perPage = null)
    {
        Log::info("Start WebsiteService > queryCareerMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Career::query(), $this->active, "id:desc")
                        ->when(!$perPage, function ($query) {
                            return $query->get();
                        })
                        ->when($perPage, function ($query) use ($perPage) {
                            return $query->paginate($perPage);
                        });
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCareerMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryCareerSingleRecord($slug)
    {
        Log::info("Start WebsiteService > queryCareerSingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(Career::query(), $this->active,"slug:$slug");
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryCareerSingleRecord | message: ". $e->getMessage());
        }
    }

    public function queryPartnerMultipleRecords()
    {
        Log::info("Start WebsiteService > queryPartnerMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(Partner::query(), $this->active, "id:desc")
                        ->get();
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryPartnerMultipleRecords | payload: " . $e->getMessage());
        }
    }

    public function queryPageSingleRecord($page)
    {
        Log::info("Start WebsiteService > queryPageSingleRecord | payload: ");
        try {
            return $this->service->queryCollectionWithSingleRecord(Page::query(), $this->active, "page:$page");
        } catch (\Exception $e) {
            Log::error("Error WebsiteService > queryPageSingleRecord | message: ". $e->getMessage());
        }
    }

    public function querySocialMediaMultipleRecords()
    {
        Log::info("Start WebsiteService > querySocialMediaMultipleRecords | payload: ");
        try {
            return $this->service->queryCollectionWithMultipleRecords(SocialMedia::query(), $this->active, "id:asc")
                ->whereType(config('dummy.social_media_type.website'))
                ->get();

        } catch (\Exception $e) {
            Log::error("Error WebsiteService > querySocialMediaMultipleRecords | message: ". $e->getMessage());
        }
    }
}
