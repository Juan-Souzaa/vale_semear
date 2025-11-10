<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Pagination View
    |--------------------------------------------------------------------------
    |
    | This view is used to render the pagination link output. You are free to
    | customize this view to match your application's design requirements.
    |
    */

    'view' => 'pagination::bootstrap-4',

    /*
    |--------------------------------------------------------------------------
    | Pagination View Presenter
    |--------------------------------------------------------------------------
    |
    | This view presenter is used to render the pagination HTML. You are free
    | to customize this view presenter to match your application's design
    | requirements.
    |
    */

    'presenter' => Illuminate\Pagination\BootstrapFourPresenter::class,

];
