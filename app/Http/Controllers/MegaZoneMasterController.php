<?php

namespace App\Http\Controllers;

use App\MegaZoneMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MegaZoneMasterController extends Controller
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
        $a = DB::table('megazone_master')->insert(['megazone_name'=>'mumbai']);
        $b = MegaZoneMaster::create(['megazone_name'=>'mumbai']);
        echo \json_encode($a);exit();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
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
     * @param  \App\MegaZoneMaster  $megaZoneMaster
     * @return \Illuminate\Http\Response
     */
    public function show(MegaZoneMaster $megaZoneMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MegaZoneMaster  $megaZoneMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(MegaZoneMaster $megaZoneMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MegaZoneMaster  $megaZoneMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MegaZoneMaster $megaZoneMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MegaZoneMaster  $megaZoneMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(MegaZoneMaster $megaZoneMaster)
    {
        //
    }
}
