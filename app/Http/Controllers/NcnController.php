<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ncn;
use App\User;
use App\UploadedFile;
use App\Types\StatusType;
use App\Types\RolesType;
use Carbon\Carbon;
use App\Notifications\NcnRequestApproval;
use App\Notifications\NcnImmediateAction;
use App\Notifications\NcnRequestDisapproved;
use App\Notifications\NcnForYourImmediateAction;
use Auth;
use PDF;
use DB;

class NcnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ncns = Ncn::with('requester')
                ->where('approver_id', Auth::user()->id)
                ->where('status', StatusType::SUBMITTED)
                ->orderBy('id', 'desc')->get();

        return $ncns;
    }
    
    /**
     * Display a listing of the submitted Ncn.
     *
     * @return \Illuminate\Http\Response
     */
    public function submitted()
    {
        $ncns = Ncn::with('requester')->where('requester_id', Auth::user()->id)->orderBy('id','desc')->get();

        return $ncns;
    }
    
    /**
     * Display a listing of the approved Ncn.
     *
     * @return \Illuminate\Http\Response
     */
    public function approvedForms(){
        $ncns = Ncn::with('requester')->where('approver_id', Auth::user()->id)
            ->where('status','!=' , StatusType::SUBMITTED)
            ->orderBy('id','desc')->get();

        return $ncns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ncn.form', ['location' => 'Non-Conformance Notification']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $rules = $request->input('non_conformity_types') == 6 ? 'required' : '';
        $request->validate([
            'company' => 'required',
            'company_location' => 'required',
            'department' => 'required',
            'approver' => 'required',
            'non_conformity_types' => 'required',
            'notification_number' => 'required',
            'recurrence_number' => 'required|integer',
            'issuance_date' => 'required',
            'non_conformity_details' => 'required',
            'attachments' => 'required',
            'others' => $rules
        ]);


        DB::beginTransaction();
        try {
            
            $ncn = new Ncn;
            $carbon = new Carbon();
    
            $ncn->requester_id = Auth::user()->id;
            $ncn->company_id = $request->input('company');
            $ncn->department_id = $request->input('department');
            $ncn->approver_id = $request->input('approver');
            $ncn->non_conformity_types = $request->input('non_conformity_types');
            $ncn->notification_number = $request->input('notification_number');
            $ncn->recurrence_number = $request->input('recurrence_number');
            $ncn->issuance_date = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('issuance_date'));
            $ncn->date_request = $carbon::now();
            $ncn->non_conformity_details = $request->input('non_conformity_details');
            $ncn->status = StatusType::SUBMITTED;
            $ncn->others = $request->input('non_conformity_types') == 6 ? $request->input('others') : '';
    
    
            if($ncn->save()){
                $approver = User::findOrFail($request->input('approver'));
                $approver->notify(new NcnRequestApproval($ncn, Auth::user()));
                
                $attachments = $request->file('attachments');   
                foreach($attachments as $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = $attachment->store('ncn');
                    $role = 'requester';
    
                    $uploadedFile = $this->uploadFiles(Auth::user()->id, $ncn->id, $path, $role,$filename);
                } 
                
                DB::commit();

                return ['redirect' => route('ncn')];
            }

        } catch (Exception $e) {
            DB::rollBack();
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
        $ncn = Ncn::findOrFail($id);
        if($ncn->status != StatusType::SUBMITTED){
            return redirect()->back();
        }
        return view('ncn.approved',['location' => 'Non-Conformance Notification']);
    }

    /**
    * Get the specified ncn by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function data($id)
    {
        $ncn = Ncn::with(['company', 'requester', 'approver', 'department', 'notified'])
                ->where('id', $id)
                ->get();

        return $ncn;
    }

    /**
    * Approve specific NCN
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function approved(Request $request)
    {

        $carbon = new Carbon();
        $ncn = Ncn::findOrFail($request->input('id'));

        if($request->input('status') == 1){
            $request->validate([
                'id' => 'required',
                'remarks' => 'required',
                'status' => 'required',
                // 'attachments' => 'required',
                'notified' => 'required'
            ]);

            $ncn->status = StatusType::APPROVED_APPROVER;
            $ncn->notified_id = $request->input('notified');
            $ncn->remarks = $request->input('remarks');
            $ncn->approved_date = $carbon::now();
            
            if($ncn->save()){
                
                $requester = User::findOrFail($ncn->requester_id);
                $notified = User::findOrFail($request->input('notified'));
                // Email sending to notified person
                $notified->notify(new NcnForYourImmediateAction($ncn,$requester,Auth::user(), $notified));
                
                $attachments = $request->file('attachments');
                if($attachments){
                    foreach($attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = $attachment->store('ncn');
                        $role = 'approver';
             
        
                        $uploadedFile = $this->uploadFiles(Auth::user()->id, $ncn->id, $path, $role,$filename);
                    } 
                }
            }
        }else{

            $request->validate([
                'id' => 'required',
                'status' => 'required'
            ]);

            $ncn->status = StatusType::DISAPPROVED_APPROVER;
            $ncn->remarks = $request->input('remarks');
            $ncn->disapproved_date = $carbon::now();
            $ncn->save();

            $requester = User::findOrFail($ncn->requester_id);
            $requester->notify(new NcnRequestDisapproved($ncn, $requester, Auth::user()));
        }

        return ['redirect' => route('ncn')];
    }

    /**
    * View the details of ccir.
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsNcn($ccir_id)
    {
        return view('ncn.view',['location' => 'Non-Conformance Notification']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ncn = Ncn::findOrfail($id);

        if($ncn->delete()){
            return $ncn;
        }
    }

    /**
     * Get approvers base on company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNcnApprovers($company, $department)
    {       
        $approvers = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::APPROVER);
        })->whereHas('companies', function($q) use ($company){
                $q->where('company_id',$company);
        })->where('department_id', $department)->get();
        return $approvers;
    }

     /**
     * Get notified personnel base on company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNotifiedPersonnel($company, $department)
    {
        $notified = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::NOTIFIED);
        })->whereHas('companies', function($q) use ($company){
            $q->where('company_id',$company);
        })->get();
        // ->where('department_id', $department)->get();
        return $notified;
    }


    /**
     * Count all submitted ncn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function countNcn()
    {
        if(Auth::user()->hasRole('administrator')){
            $ncns = Ncn::count();
        }else{
            $ncns = Ncn::whereIn('company_id', Auth::user()->companies->pluck('id'))->get()->count();
        }
        return $ncns;
    }
    
    /**
    * Display the ncn page for admin.
    *
    * @return \Illuminate\Http\Response
    */
    public function ncnAdminPage()
    {
        return view('admin.admin-ncn',['location' => 'Non-Conformance Notification']);
    }

    /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllNcns()
    {
        if(Auth::user()->hasRole('administrator')){
            $ncns = Ncn::with(['requester', 'approver', 'company'])->orderBy('id', 'desc')->get();
        }else{
            $ncns = Ncn::with(['requester', 'approver', 'company'])
            ->whereIn('company_id', Auth::user()->companies->pluck('id'))->orderBy('id','desc')->get();
        }

        return $ncns;
    }

    /**
     * Return ddr details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function ncnDetails($id)
    {
        $location = 'Non-Conformance Notification';
        return view('admin.admin-ncn-details', compact('id', 'location'));
    }

    /**
     * Generate drdr pdf to download or print
     *
     * @return \Illuminate\Http\Response
     */

    public function ncnPdf($id)
    {
        $ncn = $this->data($id);
        $pdf = PDF::loadView('admin.admin-ncn-pdf', ['ncn' => $ncn]);

        return $pdf->stream('ncn.pdf');
    }
    /**
     * Uploading files for ncn
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
        $uploadedFile->model = 'App\Ncn';
        $uploadedFile->save();
    }

    /**
     * Get requester upload files of ncn 
     *
     * @return \Illuminate\Http\Response
     */

    public function getUploadedFilesRequester($drdrId, $requesterId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $requesterId)
        ->where('form_id', $drdrId)
        ->where('role', 'requester')
        ->where('model', 'App\Ncn')
        ->get();

        return $uploadedFile;
    }

     /**
     * Get approver upload files of ncn 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUploadedFilesApprover($drdrId,$approverId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $approverId)
            ->where('form_id', $drdrId)
            ->where('role', 'approver')
            ->where('model', 'App\Ncn')
            ->get();

        return $uploadedFile;
    }

    /**
     * Get notified upload files of ncn 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUploadedFilesNotified($drdrId,$notifiedId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $notifiedId)
            ->where('form_id', $drdrId)
            ->where('role', 'notified')
            ->where('model', 'App\Ncn')
            ->get();

        return $uploadedFile;
    }

     /**
     * Download upload files/attachments of ncn 
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
     *  Search NCN by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generate(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        
        $ncns = Ncn::with(['requester', 'approver', 'company'])
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ncns;
    }

    /**
     *  Search submitted CCIR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generateSubmitted(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        
        $ncns = Ncn::with(['requester', 'company'])
                ->where('requester_id', Auth::user()->id)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ncns;
    }

    /**
     *  Search pending approval NCN by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generatePendingApproval(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        $ncns = Ncn::with(['requester', 'approver', 'company'])
                ->where('approver_id',Auth::user()->id)
                ->where('status', StatusType::SUBMITTED)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ncns;
    }

    /**
     *  Search approved NCN by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generateApproved(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        $ddrs = Ncn::with(['requester','approver', 'company'])
                ->where('approver_id', Auth::user()->id)
                ->where('status', '!=' , StatusType::SUBMITTED)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }
        
     /**
     *  Search notified NCN by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generateNotified(Request $request){

        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);

        $ncns = Ncn::with('requester')->whereNotIn('status', [StatusType::SUBMITTED, StatusType::DISAPPROVED_APPROVER])
                ->where('notified_id', Auth::user()->id)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ncns;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifiedIndex()
    {
        return view('ncn.notified',['location' => 'Non-Conformance Notification']);
    }


    /**
     * Get all approved Ncn
     *
     * @return \Illuminate\Http\Response
     */

    public function notifiedIndexData(){

        $ncns = Ncn::with('requester')->whereNotIn('status', [StatusType::SUBMITTED, StatusType::DISAPPROVED_APPROVER])->where('notified_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        
        return $ncns;
    }

    /**
     * Notified person validate Ncn
     *
     * @return \Illuminate\Http\Response
     */

    public function validateNcn(Request $request){
      
        $request->validate([
            'id' => 'required',
            'action_taken' => 'required',
            // 'attachments' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $ncn = Ncn::findOrFail($request->input('id'));

            $ncn->action_taken = $request->input('action_taken');
            $ncn->action_date = Carbon::now();
            $ncn->status = StatusType::NOTIFIED;
    
            if($ncn->save()){
                $requester = User::findOrFail($ncn->requester_id);
                $approver = User::findOrFail($ncn->approver_id);
                $emails = [$requester, $approver];
    
                $requester->notify(new NcnImmediateAction($ncn, $requester, Auth::user()));
    
                $attachments = $request->file('attachments');
                if($attachments){
                    foreach($attachments as $attachment){
                        $filename = $attachment->getClientOriginalName();
                        $path = $attachment->store('ncn');
                        $role = 'notified';
                        
                        $uploadedFile = $this->uploadFiles(Auth::user()->id, $ncn->id, $path, $role,$filename);
                    } 
                }   
                
                DB::commit();

                return ['redirect' => route('notified')];
            }

        } catch (Exception $e) {

            DB::rollBack();

        }
    }

}
