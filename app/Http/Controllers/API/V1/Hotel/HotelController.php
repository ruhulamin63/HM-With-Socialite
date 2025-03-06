<?php

namespace App\Http\Controllers\API\V1\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Traits\ApiResponser;
use App\Http\Resources\CommonResource;

class HotelController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
                $perPage = 8;
            $hotels = Hotel::paginate($perPage);

            return CommonResource::collection($hotels);
        }        
        catch(\Exception $e){
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $hotel = Hotel::create($request->all());

            return $this->success($hotel, 'Hotel created successfully.', 201);
        }        
        catch(\Exception $e){
            return $this->error($e->getMessage(), 500);
        }    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $hotel = Hotel::findOrFail($id);

            return $this->success($hotel, 'Hotel retrieved successfully.');
        }        
        catch(\Exception $e){
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $hotel = Hotel::findOrFail($id);
            $hotel->update($request->all());

            return $this->success($hotel, 'Hotel updated successfully.');
        }        
        catch(\Exception $e){
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
