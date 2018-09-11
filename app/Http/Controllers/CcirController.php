<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ccir;
use App\UploadedFile;
use App\User;
use Carbon\Carbon;
use App\Setting;
use PDF;
use App\Notifications\RequesterSubmitCcir;
use App\Types\StatusType;
use App\Types\RolesType;


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
        $ccirs->brand_name = 'Sample brand name';
        $ccirs->product_control_number = $request->input('product_control_number');
        $ccirs->date_request = $carbon::now();
        $ccirs->delivery_date = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('delivery_date'));   
        $ccirs->nature_of_complaint = $request->input('nature_of_complaint');
        $ccirs->other_details = $request->input('other_details');
        $ccirs->affected_quantity = $request->input('affected_quantity');
        $ccirs->quality_of_sample = $request->input('quality_of_sample');
        $ccirs->returned_date =  \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('returned_date'));
        $ccirs->status = StatusType::SUBMITTED;

        // $setting = Setting::findOrFail('1');
        // $setting->notify(new RequesterSubmitCcir($ccirs));

        if($ccirs->save()){
            
            $company_id = $ccirs->company_id;
            $mr = User::whereHas('roles', function($q) {
                $q->where('role_id', RolesType::MR);
            })->whereHas('companies', function($q) use ($company_id) {
                $q->where('company_id',$company_id);
            })->get();
    
            \Notification::send($mr, new RequesterSubmitCcir($ccirs));

            $attachments = $request->file('attachments');   
            foreach($attachments as $attachment){
                $filename = $attachment->getClientOriginalName();
                $path = $attachment->store('ccir');
                $role = 'requester';

                $uploadedFile = $this->uploadFiles(Auth::user()->id, $ccirs->id, $path, $role,$filename);
            } 
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
    * Get the specified ncn by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function data($id)
    {
        $ccir = Ccir::with(['company', 'requester'])
                ->where('id', $id)
                ->get();

        return $ccir;
    }

    /**
    * View the details of ccir.
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsCcir($ccir_id)
    {
        return view('ccir.view');
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
        if(Auth::user()->hasRole('administrator')){
            $ccirs = Ccir::count();
        }else{
            $ccirs = Ccir::whereIn('company_id', Auth::user()->companies->pluck('id'))->get()->count();
        }

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
        $ccirs = Ccir::with(['company', 'requester'])->orderBy('id', 'desc')->get();

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
        $ccir = $this->data($id);
        $pdf = PDF::loadView('admin.admin-ccir-pdf', ['ccir' => $ccir]);

        return $pdf->stream('ccir.pdf');
    }

        /**
     * Uploading files for ccir
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($userId,$ncnId,$path,$role,$filename)
    {
        $uploadedFile = new UploadedFile;
        $uploadedFile->user_id = $userId;
        $uploadedFile->form_id = $ncnId;
        $uploadedFile->role = $role;
        $uploadedFile->file_path =  $path;
        $uploadedFile->file_name = $filename;
        $uploadedFile->model = 'App\Ccir';
        $uploadedFile->save();
    }

    /**
     * Get requester upload files of ccir 
     *
     * @return \Illuminate\Http\Response
     */

    public function getUploadedFilesRequester($ccirId, $requesterId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $requesterId)
        ->where('form_id', $ccirId)
        ->where('role', 'requester')
        ->where('model', 'App\Ccir')
        ->get();

        return $uploadedFile;
    }

     /**
     * Get approver upload files of ccir 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUploadedFilesApprover($ccirId,$verifierId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $verifierId)
            ->where('form_id', $ccirId)
            ->where('role', 'verifier')
            ->where('model', 'App\Ccir')
            ->get();

        return $uploadedFile;
    }

     /**
     * Download upload files/attachments of ccir 
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment($fileId)
    {
        $uploadedFile = UploadedFile::findOrfail($fileId);

        ob_end_clean();
        return response()->download(storage_path("app/public/".$uploadedFile->file_path), $uploadedFile->file_name);
    }

    /**
     *  Search CCIR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generate(Request $request, $startDate,$endDate)
    {
        $from = \DateTime::createFromFormat('D M d Y H:i:s e+', $startDate);
        $to =  \DateTime::createFromFormat('D M d Y H:i:s e+', $endDate);
        $ccirs = Ccir::with(['requester', 'company'])
                ->where('date_request', '>=', $from)
                ->where('date_request' ,'<=', $to)
                ->orderBy('id', 'desc')->get();

        return $ccirs;
    }

    /**
     *  Validate submitted CCIR
     *
     * @return \Illuminate\Http\Response
     */
    public function validateCCir(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);

        $ccir = Ccir::findOrFail($request->id);
        $carbon = new Carbon();
        if($ccir->status != StatusType::CCIR_VALID && $ccir->status != StatusType::CCIR_INVALID){
            if($request->status == 1){
                $request->validate(['car_number'=> 'required']);
                $ccir->car_number = $request->car_number;
                $ccir->status = StatusType::CCIR_VALID;
                $ccir->verifier_id = Auth::user()->id;
                $ccir->verified_date = $carbon::now();
            }else{ 
                $ccir->status = StatusType::CCIR_INVALID;
                $ccir->cancel_date = $carbon::now();
            }
            if($ccir->save()){
                return ['redirect' => route('admin.ccirs')];   
            }
        }
        return redirect()->back();
    
    }

}
