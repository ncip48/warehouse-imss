<?php

namespace App\Http\Controllers;

use App\Models\DetailPR;
use App\Models\DetailSpph;
use App\Models\Keproyekan;
use App\Models\Purchase_Order;
use App\Models\PurchaseRequest;
use App\Models\Spph;
use App\Models\SpphLampiran;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SpphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $search = $request->q;

        if (Session::has('selected_warehouse_id')) {
            $warehouse_id = Session::get('selected_warehouse_id');
        } else {
            $warehouse_id = DB::table('warehouse')->first()->warehouse_id;
        }
        $spphes = Spph::paginate(50);
        foreach ($spphes as $key => $item) {
            $id = json_decode($item->vendor_id);
            $item->vendor = Vendor::whereIn('id', $id)->get();
            $item->vendor = $item->vendor->map(function ($item) {
                return $item->nama;
            });
            //change $item->vendor collection to array
            $item->vendor = $item->vendor->toArray();
            $item->vendor = implode(', ', $item->vendor);

            //lampiran bisa lebih dari 1
            $lampiran = SpphLampiran::where('spph_id', $item->id)->pluck('file')->toArray();
            $item->lampiran = implode(', ', $lampiran);
            // $item->lampiran = json_decode($item->lampiran); 
        }
        $vendors = Vendor::all();
        // dd($spphes);
        if ($search) {
            $spphes = Spph::where('tanggal_spph', 'LIKE', "%$search%")->paginate(50);
        }

        if ($request->format == "json") {
            $categories = Spph::where("warehouse_id", $warehouse_id)->get();

            return response()->json($categories);
        } else {
            return view('spph.spph', compact('spphes', 'vendors'));
        }
    }

    public function indexApps(Request $request)
    {
        $search = $request->q;

        if (Session::has('selected_warehouse_id')) {
            $warehouse_id = Session::get('selected_warehouse_id');
        } else {
            $warehouse_id = DB::table('warehouse')->first()->warehouse_id;
        }

        $spphes = Spph::paginate(50);
        $vendors = Vendor::all();

        if ($search) {
            $spphes = Spph::where('tanggal_spph', 'LIKE', "%$search%")->paginate(50);
        }

        if ($request->format == "json") {
            $categories = Spph::where("warehouse_id", $warehouse_id)->get();

            return response()->json($categories);
        } else {
            return view('home.apps.logistik.spph', compact('spphes', 'vendors'));
        }
    }

    function FunctionCountPages($path)
    {
        $pdftextfile = file_get_contents($path);
        $pagenumber = preg_match_all("/\/Page\W/", $pdftextfile, $dummy);
        return $pagenumber;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $spph = $request->id;
        // if (Session::has('selected_warehouse_id')) {
        //     $warehouse_id = Session::get('selected_warehouse_id');
        // } else {
        //     $warehouse_id = DB::table('warehouse')->first()->warehouse_id;
        // }
        // dd($request->all());

        $request->validate([
            'nomor_spph' => 'required',
            'id_pr' => 'required',
            'nomor_pr' => 'required',
            // 'lampiran' => 'required',
            'vendor' => 'required',
            'tanggal_spph' => 'required',
            'batas_spph' => 'required',
            'perihal' => 'required',
            // 'penerima' => 'required',
            // 'alamat' => 'required'
        ], [
            'nomor_spph.required' => 'Nomor SPPH harus diisi',
            'id_pr.required' => 'ID PR harus diisi',
            'nomor_pr.required' => 'Nomor PR harus diisi',
            // 'lampiran.required' => 'Lampiran harus diisi',
            'vendor.required' => 'Vendor harus diisi',
            'tanggal_spph.required' => 'Tanggal SPPH harus diisi',
            'batas_spph.required' => 'Batas SPPH harus diisi',
            'perihal.required' => 'Perihal harus diisi',
            'penerima.required' => 'Penerima harus diisi',
            'alamat.required' => 'Alamat harus diisi'
        ]);

        $data = [
            'nomor_spph' => $request->nomor_spph,
            'id_pr' => $request->id_pr,
            'nomor_pr' => $request->nomor_pr,
            'vendor_id' => json_encode($request->vendor),
            'tanggal_spph' => $request->tanggal_spph,
            'batas_spph' => $request->batas_spph,
            'perihal' => $request->perihal,
            'penerima' => json_encode($request->penerima),
            'alamat' => json_encode($request->alamat)
        ];

        // Ubah data vendor menjadi ID berdasarkan nama
        $vendorNames = json_decode($data['vendor_id']);
        $vendors = Vendor::whereIn('nama', $vendorNames)->pluck('id')->toArray();
        $data['vendor_id'] = json_encode($vendors);


        // dd($data);

        if (empty($spph)) {
            $add = Spph::create($data);

            // Check if 'lampiran' exists and is not null
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                foreach ($files as $file) {
                    $file_name = rand() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('lampiran'), $file_name);
                    SpphLampiran::create([
                        'spph_id' => $add->id,
                        'file' => $file_name,
                        'tipe' => $this->FunctionCountPages(public_path('lampiran/' . $file_name))
                    ]);
                }
            }

            if ($add) {
                return redirect()->route('spph.index')->with('success', 'SPPH berhasil ditambahkan');
            } else {
                return redirect()->route('spph.index')->with('error', 'SPPH gagal ditambahkan');
            }
        } else {
            $update = Spph::where('id', $spph)->update($data);
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                foreach ($files as $file) {
                    $file_name = rand() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('lampiran'), $file_name);
                    SpphLampiran::create([
                        'spph_id' => $spph,
                        'file' => $file_name,
                        'tipe' => $this->FunctionCountPages(public_path('lampiran/' . $file_name))
                    ]);
                }
            }
            // Ambil nama lampiran yang diinginkan dari request
            $nama_lampiran_baru = explode(', ', $request->nama_lampiran); //masih error


            // Ambil semua lampiran yang terkait dengan $spph dari database
            $existing_files = explode(', ', $request->lampiran_awal);

            // dd($nama_lampiran_baru);

            // Loop untuk setiap lampiran yang sudah ada
            foreach ($existing_files as $existing_file) {
                // Jika lampiran tidak termasuk dalam nama lampiran yang baru diupload, hapus dari database dan filesystem
                if (!in_array($existing_file, $nama_lampiran_baru)) {
                    // Hapus dari database
                    SpphLampiran::where('spph_id', $spph)->where('file', $existing_file)->delete();

                    // Hapus dari filesystem jika perlu
                    // $file_path = public_path('lampiran/' . $existing_file);
                    // if (file_exists($file_path)) {
                    //     unlink($file_path);
                    // }
                }
            }

            // if ($request->hasFile('lampiran')) {
            //     $files = $request->file('lampiran');
            //     foreach ($files as $file) {
            //         $file_name = rand() . '.' . $file->getClientOriginalExtension();
            //         $file->move(public_path('lampiran'), $file_name);

            //         // Find the existing SpphLampiran record to update
            //         $lampiran = SpphLampiran::where('spph_id', $spph)->first();

            //         if ($lampiran) {
            //             // Update the existing record
            //             $lampiran->update([
            //                 'file' => $file_name,
            //                 'tipe' => $this->FunctionCountPages(public_path('lampiran/' . $file_name))
            //             ]);
            //         }
            //     }
            // }


            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'SPPH berhasil diupdate',
            //     'data' => $update
            // ]);

            if ($update) {
                return redirect()->route('spph.index')->with('success', 'SPPH berhasil diupdate');
            } else {
                return redirect()->route('spph.index')->with('error', 'SPPH gagal diupdate');
            }
        }

        // return redirect()->route('spph.index')->with('success', 'SPPH berhasil disimpan');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
        $delete_spph = $request->id;
        $delete_spph = DB::table('spph')->where('id', $delete_spph)->delete();

        if ($delete_spph) {
            return redirect()->route('spph.index')->with('success', 'Data SPPH berhasil dihapus');
        } else {
            return redirect()->route('spph.index')->with('error', 'Data SPPH gagal dihapus');
        }
    }

    public function hapusMultipleSpph(Request $request)
    {
        if ($request->has('ids')) {
            Spph::whereIn('id', $request->input('ids'))->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getDetailSPPH(Request $request)
    {
        $id = $request->id;
        $spph = Spph::where('id', $id)->first();
        $vendor = json_decode($spph->vendor_id);
        $vendor = Vendor::whereIn('id', $vendor)->get();
        $vendor = $vendor->map(function ($item) {
            return $item->nama;
        });
        $vendor = $vendor->toArray();
        $vendor = implode(', ', $vendor);
        $spph->penerima = $vendor;

        $spph->details = DetailSpph::where('spph_id', $id)
            ->leftJoin('detail_pr', 'detail_pr.id', '=', 'detail_spph.id_detail_pr')
            ->get();

        $spph->details = $spph->details->map(function ($item) {
            $item->spek = $item->spek ? $item->spek : '';
            $item->keterangan = $item->keterangan ? $item->keterangan : '';
            $item->kode_material = $item->kode_material ? $item->kode_material : '';
            // $item->lampiran = $item->lampiran ? $item->lampiran : '';

            // // Start Get lampiran for each detail
            // $lampiran = SpphLampiran::where('spph_id', $item->id)->get();
            // $item->lampiran = $lampiran->map(function ($lampiran) {
            // $item->lampiran = $item->lampiran ? $item->lampiran : '';
            //     // dd($lampiran);
            //     return $lampiran->file; // Assuming `file_name` is the column name
            // })->toArray();
            // //End Get Lampiran for detail

            return $item;
        });

        return response()->json([
            'spph' => $spph
        ]);
    }


    public function getProductPR(Request $request)
    {
        // dd($request);
        $id_pr = $request->id_pr; // Ambil id_pr dari request
        $proyek = strtolower($request->proyek);

        // Ambil DetailPR yang sesuai dengan id_pr
        $products = DetailPR::where('id_pr', $id_pr)->get();

        // Proses setiap produk
        $products = $products->map(function ($item) {
            $item->spek = $item->spek ? $item->spek : '';
            $item->keterangan = $item->keterangan ? $item->keterangan : '';
            $item->kode_material = $item->kode_material ? $item->kode_material : '';
            $item->nomor_spph = Spph::where('id', $item->id_spph)->first()->nomor_spph ?? '';
            $item->pr_no = PurchaseRequest::where('id', $item->id_pr)->first()->no_pr ?? '';
            $item->po_no = Purchase_Order::where('id', $item->id_po)->first()->no_po ?? '';
            $item->nama_proyek = Keproyekan::where('id', $item->id_proyek)->first()->nama_proyek ?? '';
            return $item;
        });

        // Filter produk berdasarkan nama proyek
        $products = $products->filter(function ($item) use ($proyek) {
            return strpos(strtolower($item->nama_proyek), $proyek) !== false;
        });

        // Kembalikan hasil dalam bentuk JSON
        return response()->json([
            'products' => $products
        ]);
    }


    // public function getProductPR(Request $request)
    // {
    //     $proyek = $request->proyek;
    //     $proyek = strtolower($proyek);
    //     $products = DetailPR::all();

    //     $products = $products->map(function ($item) {
    //         $item->spek = $item->spek ? $item->spek : '';
    //         $item->keterangan = $item->keterangan ? $item->keterangan : '';
    //         $item->kode_material = $item->kode_material ? $item->kode_material : '';
    //         $item->nomor_spph = Spph::where('id', $item->id_spph)->first()->nomor_spph ?? '';
    //         $item->pr_no = PurchaseRequest::where('id', $item->id_pr)->first()->no_pr ?? '';
    //         $item->po_no = Purchase_Order::where('id', $item->id_po)->first()->no_po ?? '';
    //         $item->nama_proyek = Keproyekan::where('id', $item->id_proyek)->first()->nama_proyek ?? '';
    //         return $item;
    //     });

    //     $products = $products->filter(function ($item) use ($proyek) {
    //         return strpos(strtolower($item->nama_proyek), $proyek) !== false;
    //     });

    //     return response()->json([
    //         'products' => $products
    //     ]);
    // }


    public function spphPrint(Request $request)
    {
        $id = $request->spph_id;
        $spph = Spph::where('id', $id)->first();
        $spph->details = DetailSpph::where('spph_id', $id)
            ->leftjoin('detail_pr', 'detail_pr.id', '=', 'detail_spph.id_detail_pr')
            ->get();
        $spph->tanggal_spph = Carbon::parse($spph->tanggal_spph)->isoFormat('D MMMM Y');
        $spph->batas_spph = Carbon::parse($spph->batas_spph)->isoFormat('D MMMM Y');

        $vendor = json_decode($spph->vendor_id);
        $vendor_name = Vendor::whereIn('id', $vendor)->get();
        $vendor_name = $vendor_name->map(function ($item) {
            return $item->nama;
        });
        $vendor_name = $vendor_name->toArray();

        $vendor_alamat = Vendor::whereIn('id', $vendor)->get();
        $vendor_alamat = $vendor_alamat->map(function ($item) {
            return $item->alamat;
        });
        $vendor_alamat = $vendor_alamat->toArray();

        $newObjects = [];

        foreach ($vendor_name as $key => $value) {
            $newObject = new \stdClass();
            $newObject->nama = $value;
            $newObject->alamat = $vendor_alamat[$key];
            array_push($newObjects, $newObject);
        }

        $lampiran = SpphLampiran::where('spph_id', $spph->id)->get();
        $spph->lampiran = $lampiran->count();

        $spphs = $newObjects;
        $count = count($spphs);

        $pdf = PDF::loadview('spph.spph_print', compact('spph', 'spphs', 'count', 'lampiran'));
        $no_spph = $spph->nomor_spph;
        $pdf->setPaper('A4', 'Potrait');
        return $pdf->stream('SPPH_' . $no_spph . '.pdf');
    }


    //coding asli spph print
    // public function spphPrint(Request $request)
    // {
    //     $id = $request->spph_id;
    //     $spph = Spph::where('id', $id)->first();
    //     $spph->details = DetailSpph::where('spph_id', $id)
    //         ->leftjoin('detail_pr', 'detail_pr.id', '=', 'detail_spph.id_detail_pr')
    //         ->get();
    //     $spph->tanggal_spph = Carbon::parse($spph->tanggal_spph)->isoFormat('D MMMM Y');
    //     $spph->batas_spph = Carbon::parse($spph->batas_spph)->isoFormat('D MMMM Y');

    //     // dd($spph);

    //     // $page_count = 0;
    //     // $dummy = PDF::loadview('spph_print', compact('spph', 'page_count'));
    //     // $dummy->setPaper('A4', 'Potrait');
    //     // $no_spph = $spph->nomor_spph;
    //     // $dummy->render();
    //     // $page_count = $dummy->get_canvas()->get_page_count();
    //     // $pdf = PDF::loadview('spph_print', compact('spph', 'page_count'));

    //     $vendor = json_decode($spph->vendor_id);
    //     $vendor_name = Vendor::whereIn('id', $vendor)->get();
    //     $vendor_name = $vendor_name->map(function ($item) {
    //         return $item->nama;
    //     });
    //     $vendor_name = $vendor_name->toArray();

    //     $vendor_alamat = Vendor::whereIn('id', $vendor)->get();
    //     $vendor_alamat = $vendor_alamat->map(function ($item) {
    //         return $item->alamat;
    //     });
    //     $vendor_alamat = $vendor_alamat->toArray();

    //     $newObjects = [];

    //     //push to newObject with nama=vendor_name, alamat=vendor_alamat
    //     foreach ($vendor_name as $key => $value) {
    //         $newObject = new \stdClass();
    //         $newObject->nama = $value;
    //         $newObject->alamat = $vendor_alamat[$key];
    //         array_push($newObjects, $newObject);
    //     }

    //     $lampiran = SpphLampiran::where('spph_id', $spph->id)->get();
    //     //sum in tipe column
    //     $spph->lampiran = $lampiran->count();
    //     // dd($spph->all());

    //     // $files = [];
    //     // foreach ($lampiran as $key => $value) {
    //     //     $file = public_path('lampiran/' . $value->file);
    //     //     $page_count = $this->FunctionCountPages($file);
    //     //     $value->page_count = $page_count;
    //     //     array_push($files, $value);
    //     // }

    //     // foreach ($files as $key => $value) {
    //     //     $pdf->prependPDF($value->file);
    //     // }

    //     $spphs = $newObjects;
    //     $count = count($spphs);

    //     $pdf = PDF::loadview('spph.spph_print', compact('spph', 'spphs', 'count'));
    //     $no_spph = $spph->nomor_spph;
    //     $pdf->setPaper('A4', 'Potrait');
    //     return $pdf->stream('SPPH_' . $no_spph . '.pdf');
    // }



    function tambahSpphDetail(Request $request)
    {
        $id = $request->spph_id;
        $selected = $request->selected_id;

        if (empty($selected)) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Pilih barang terlebih dahulu'
            ]);
        }

        //foreach selected_id

        foreach ($selected as $key => $value) {
            $id_barang = $value;
            $add = DetailSpph::create([
                'spph_id' => $id,
                'id_detail_pr' => $id_barang
            ]);

            $update = DetailPR::where('id', $id_barang)->update([
                'id_spph' => $id,
                'status' => 1,
            ]);
        }

        $spph = Spph::where('id', $id)->first();
        // $spph->penerima = json_decode($spph->penerima);
        // $spph->penerima = implode(', ', $spph->penerima);

        $spph->details = DetailSpph::where('spph_id', $id)
            ->leftjoin('detail_pr', 'detail_pr.id', '=', 'detail_spph.id_detail_pr')
            ->get();
        $spph->details = $spph->details->map(function ($item) {
            $item->spek = $item->spek ? $item->spek : '';
            $item->keterangan = $item->keterangan ? $item->keterangan : '';
            $item->kode_material = $item->kode_material ? $item->kode_material : '';
            $item->nomor_spph = Spph::where('id', $item->id_spph)->first()->nomor_spph ?? '';
            return $item;
        });

        return response()->json([
            'success' => TRUE,
            'message' => 'Barang berhasil ditambahkan',
            'spph' => $spph
        ]);
    }
    public function nopr()
    {
        $data = PurchaseRequest::where('no_pr', 'LIKE', '%' . request('q') . '%')->paginate(10000);
        return response()->json($data);
    }
}
