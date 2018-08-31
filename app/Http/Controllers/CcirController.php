<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ccir;
use Carbon\Carbon;
use App\Setting;
use PDF;
use App\Notifications\RequesterSubmitNcn;
use App\Types\StatusType;

class CcirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ccirs = Ccir::with('requester')->orderBy('id', 'desc')->get();

        return $ccirs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ccir.form');
    }

     /**
     * Displaying list of submitted Ccir
     *
     * @return \Illuminate\Http\Response
     */
    public function submitted()
    {
        $ccirs = Ccir::with('requester')->where('requester_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return $ccirs;
    }

    /**
     * Displaying list of approved Ccir
     *
     * @return \Illuminate\Http\Response
     */
    public function approvedForms()
    {
        $ccirs = Ccir::with('requester')->where('verifier_id', Auth::user()->id)
            ->where('status', StatusType::VERIFIED_VERIFIER)->orderBy('id', 'desc')->get();

        return $ccirs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  = $request->validate([
            'complainant' => 'required',
            'commodity' => 'required',
            'product_control_number' => 'required',
            'delivery_date' => 'required',
            'nature_of_complaint' => 'required',
            'other_details' => 'required',
            'delivery_date' => 'required',
            'affected_quantity' => 'required',
            'quality_of_sample' => 'required',
            'returned_date' => 'required'
        ]);

        $ccirs  = new Ccir;
        $carbon = new Carbon();
        $ccirs->requester_id = Auth::user()->id;
        $ccirs->company_id = 1;
        $ccirs->complainant = $request->input('complainant');
        $ccirs->commodity = $request->input('commodity');
        $ccirs->brand_name = $request->input('commodity');
        $ccirs->product_control_number = $request->input('product_control_number');
        $ccirs->request_date = $carbon::now();
        $ccirs->delivery_date = $carbon::parse($request->input('delivery_date'))->toDateTimeString();
        $ccirs->nature_of_complaint = $request->input('nature_of_complaint');
        $ccirs->other_details = $request->input('other_details');
        $ccirs->affected_quantity = $request->input('affected_quantity');
        $ccirs->attached_file = 'saadadadadad';
        $ccirs->quality_of_sample = $request->input('quality_of_sample');
        // $ccirs->attached_file = $request->input('attached_file');
        $ccirs->returned_date = $carbon::parse($request->input('returned_date'))->toDateTimeString();
        $ccirs->verifier_id = $request->input('verifier_id');
        $ccirs->status = 1;

        // $setting = Setting::findOrFail(1);
        // $setting->notify(new RequesterSubmitNcn($$ccirs));


        if($ccirs->save()){
            return ['redirect' => route('ccir')];
        }

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
    public function destroy($id)
    {
        $ccir = Ccir::findOrFail($id);

        if($ccir->delete()){
            return $ccir;
        }
    }

    /**
     * Count all submitted ccir
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function countCcir()
    {
        $ccirs = Ccir::count();

        return $ccirs;
    }

    /**
     * Return ccir page for admin
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ccirAdminPage()
    {
        return view('admin.admin-ccir');
    }

    /**
     * Get all ccir submitted for admin
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllCcirs()
    {
        $ccirs = Ccir::with(['company', 'requester'])->get();

        return $ccirs;
    }
     /**
     * Return ccir details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function ccirDetails($id)
    {
        return view('admin.admin-ccir-details', ['id' => $id]);
    }

    /**
     * Generate ccir pdf to download or print
     *
     * @return \Illuminate\Http\Response
     */

    public function ccirPdf($id)
    {
        $pdf = PDF::loadView('admin.admin-ccir-pdf');

        return $pdf->stream('ccir.pdf');
    }
}
