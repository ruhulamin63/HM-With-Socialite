<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class APIController extends Controller
{
    use ApiResponser;

    public $perPage;
    public $page;
    public $limit;
    public $orderBy;
    public $orderDirection;

    /**
     * The constructor function initializes properties for pagination and sorting based on request
     * parameters in PHP.
     */
    public function __construct()
    {
        $this->perPage = request()->has('per_page') ? (int) request()->per_page : 10;
        $this->page = request()->has('page') ? (int) request()->page : 1;
        $this->limit = $this->perPage;
        $this->orderBy = request()->has('order_by') ? request()->order_by : 'id';
        $this->orderDirection = request()->has('order_direction') ? request()->order_direction : 'asc';
    }
}
