<?php

namespace App\Http\Controllers;

use App\RegionMaster;
use Illuminate\Http\Request;

class RegionMasterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RegionMaster  $regionMaster
     * @return \Illuminate\Http\Response
     */
    public function show(RegionMaster $regionMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RegionMaster  $regionMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionMaster $regionMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RegionMaster  $regionMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegionMaster $regionMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RegionMaster  $regionMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegionMaster $regionMaster)
    {
        //
    }
}
