<?php

namespace App\Http\Controllers;

use App\ZoneMaster;
use Illuminate\Http\Request;

class ZoneMasterController extends Controller
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
     * @param  \App\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function show(ZoneMaster $zoneMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(ZoneMaster $zoneMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ZoneMaster $zoneMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ZoneMaster  $zoneMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZoneMaster $zoneMaster)
    {
        //
    }
}
