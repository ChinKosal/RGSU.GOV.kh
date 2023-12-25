<?php

return [
    'name' => 'Category',
    'title' => 'Category Management',
    'title_subcategory' => 'Subcategory',
    'header' => [
        'title' => 'Total Categories : :total',
    ],
    'filter' => [
        'search' => 'Search...',
        'all' => 'All',
        'role' => 'Select Role',
    ],
    'button' => [
        'create' => 'Create New',
        'reload' => 'Refresh',
        'search' => 'Search',
    ],
    'empty' => [
        'title' => 'Category is empty',
        'description' => 'You can create a new Category by clicking the button below.',
    ],
    'message' => [
        'create' => [
            'success' => 'Category created success',
        ],
        'update' => [
            'success' => 'Category updated success',
        ],
        'delete' => [
            'success' => 'Category deleted success',
        ],
        'status' => [
            'success' => 'Status updated success',
        ],
        'error' => 'Oops! Something went wrong',
        'change_password' => 'Password has been changed',
    ],
    'form' => [
        'title' => [
            'change_password' => 'Change Password',
            'create' => 'Create New Category',
            'update' => 'Update Category',
        ],
        'status' => [
            'label' => 'Status',
            'placeholder' => 'Select status',
        ],
        'button' => [
            'update' => 'Update',
            'save' => 'Save',
            'cancel' => 'Cancel',
        ],
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Please enter name'
        ],
        'type' => [
            'label' => 'Type',
            'placeholder' => 'Please select status'
        ],
        'ordering' => [
            'label' => 'Ordering',
            'placeholder' => 'Please enter ordering'
        ],
    ],
];
