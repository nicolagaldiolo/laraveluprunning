<?php

// PAGINATION
Route::get('pagination', function(){
    $tasks_paginate = \App\Task::paginate(5);
    $tasks_simple_paginate = \App\Task::simplePaginate(5);

    return view('pagination.pagination', compact('tasks_paginate', 'tasks_simple_paginate'));
});

// MANUAL PAGINATION
Route::get('manual-pagination', function(\Illuminate\Http\Request $request){

    $tasks_manual_paginate = \Illuminate\Support\Facades\DB::table('tasks')->select('*')->get();
    $perPage = 5;
    $currentPage = $request->input('page', 1);
    $offsetPages = $currentPage - 1;
    $paginationOption = ['path' => '/manual-pagination'];

    $totalElements = $tasks_manual_paginate->count();
    $tasks_paginate = $tasks_manual_paginate->slice($offsetPages * $perPage, $perPage);

    $paginator = new Illuminate\Pagination\Paginator($tasks_manual_paginate, $perPage, $currentPage, $paginationOption);
    $lengthAwarePaginator = new \Illuminate\Pagination\LengthAwarePaginator($tasks_manual_paginate, $totalElements, $perPage, $currentPage, $paginationOption);

    return view('pagination.manual_pagination', compact('tasks_paginate', 'paginator', 'lengthAwarePaginator'));
});