<?php

namespace App\Http\Controllers\Admin;


use App\Services\QueryService;
use App\Http\Controllers\Controller;
use App\Models\ApplyService;
use App\Models\Customer;
use App\Models\News;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserQuiz;

class DashboardController extends Controller
{

    private $queryService;
    public function __construct(QueryService $queryService)
    {
        $this->queryService = $queryService;
    }

    public function index()
    {
        return redirect()->route('admin-slider-list');

        $data["dashboard"] = [
            (object)[
                'name' => 'total_user',
                'icon' => 'users',
                'value' => $this->countUser(),
                'custom_class' => 'bg-primary',
                'link' => route('admin-admin-list'),
            ],
        ];

        return view("admin::pages.dashboard", $data);
    }
    public function countUser()
    {
        return User::where('type', 'admin')->whereNot('role', 'super_admin')->count();
    }
}
