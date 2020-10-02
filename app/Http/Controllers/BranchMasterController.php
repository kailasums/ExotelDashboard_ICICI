<?php

namespace App\Http\Controllers;

use App\BranchMaster;
use Illuminate\Http\Request;

class BranchMasterController extends Controller
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
     * @param  \App\BranchMaster  $branchMaster
     * @return \Illuminate\Http\Response
     */
    public function show(BranchMaster $branchMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BranchMaster  $branchMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchMaster $branchMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BranchMaster  $branchMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchMaster $branchMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BranchMaster  $branchMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchMaster $branchMaster)
    {
        //
    }
}
