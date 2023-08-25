<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SjnController extends Controller
{
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
        // $sjn = DB::table('sjn')->get();
        // return view('sjnDetail', compact('sjn'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sjn_id = $request->id;
        if (Session::has('selected_warehouse_id')) {
            $warehouse_id = Session::get('selected_warehouse_id');
        } else {
            $warehouse_id = DB::table('warehouse')->first()->warehouse_id;
        }
        $request->validate(
            [
                'no_sjn' => 'required',
            ],
            [
                'no_sjn.required' => 'No. SJN harus diisi',
            ]
        );

        if (empty($sjn_id)) {
            DB::table('sjn')->insert([
                'no_sjn' => $request->no_sjn,
                'warehouse_id' => $warehouse_id,
                "user_id"  => Auth::user()->id,
                "datetime" => Carbon::now()->setTimezone('Asia/Jakarta')
            ]);

            return redirect()->route('sjn')->with('success', 'Data SJN berhasil ditambahkan');
        } else {
            DB::table('sjn')->where('sjn_id', $sjn_id)->update([
                'no_sjn' => $request->no_sjn,
                'warehouse_id' => $warehouse_id,
                "user_id" => Auth::user()->id,
            ]);

            return redirect()->route('sjn')->with('success', 'Data SJN berhasil diubah');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        DB::table('sjn')->where('sjn_id', $id)->delete();

        return redirect()->route('sjn')->with('success', 'Data SJN berhasil dihapus');
    }

    public function getDetailSjn(Request $request)
    {
        $id = $request->id;
        $sjn = DB::table('sjn')->where('sjn_id', $id)->first();
        $sjn->products = DB::table('sjn_details')->where('sjn_id', $id)->leftJoin('products', 'products.product_id', '=', 'sjn_details.product_id')->leftJoin('keproyekan', 'keproyekan.id', '=', 'products.keproyekan_id')->select('sjn_details.*', 'products.product_name', 'products.satuan', 'products.product_code', 'products.spesifikasi', 'keproyekan.nama_proyek')->get();
        return response()->json([
            'sjn' => $sjn,
        ]);
    }
}
