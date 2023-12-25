@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper">
        @include('admin::shared.header', [
            'title' => __('dashboard.dashboard'),
            'header_name' => __('dashboard.dashboard'),
        ])

        <div class="dashboard-admin">
            <div class="dashboard-wrapper">
                <div class="dashboard-body">
                    <div class="dashboard-list">
                        <div class="dashboard-row">
                            <div class="item !bg-red-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Total Customers</span>
                                        <h3>1</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="users"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                            <div class="item !bg-blue-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Total Products</span>
                                        <h3>1</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="shopping-bag"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                            <div class="item !bg-orange-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Total Orders</span>
                                        <h3>38</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                            <div class="item !bg-red-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Pending Orders</span>
                                        <h3>38</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                            <div class="item !bg-green-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Completed Orders</span>
                                        <h3>38</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                            <div class="item !bg-orange-500">
                                <div class="item-body">
                                    <div class="left">
                                        <span>Cancelled Orders</span>
                                        <h3>38</h3>
                                    </div>
                                    <div class="right">
                                        <i data-feather="shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="item-footer">
                                    <span>View Detail</span>
                                    <i data-feather="arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-footer"></div>
                </div>
            </div>
        </div>
    </div>
@stop
