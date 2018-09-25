<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Drdr;
use App\User;
use App\UploadedFile;
use App\Types\RequestType;
use App\Types\StatusType;
use App\Types\RolesType;
use Auth;
use Carbon\Carbon;
use App\Notifications\RequesterSubmitDrdr;
use App\Notifications\ReviewerReviewedDrdr;
use App\Notifications\ReviewerDisapprovedDrdr;
use App\Notifications\ApproverNotifyMrDrdr;
use App\Notifications\MrMarkAsDistributedDrdr;
use App\Notifications\ApproverDisapprovedDrdr;
use App\Notifications\SchedulingNotifyApproverDrdr;
use App\Notifications\SchedulingNotifyReviewerDrdr;

use PDF;
use Response;

class DrdrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer', 'approver')
            ->where('reviewer_id',$id)
            ->orWhere('approver_id', $id)
            ->orderBy('id', 'desc')->get();
         return $drdrs;
    }

    /**
     * Display a listing of the pending reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingReviews()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer')
            ->where('reviewer_id',$id)
            ->where('status', StatusType::SUBMITTED)
            ->orderBy('id', 'desc')->get();
         return $drdrs;
    }

    /**
     * Display a listing of the reviewed forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewedForms()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer', 'approver', 'company')
            ->where('reviewer_id',$id)
            ->where('status', '!=', StatusType::SUBMITTED)
            ->orderBy('id', 'desc')->get();
         return $drdrs;
    }

    /**
     * Display a listing of the pending approval forms.
     *
     * @return \Illuminate\Http\Response
     */

    public function pendingApprovals()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer', 'company')
            ->where('approver_id',$id)
            ->where('status', StatusType::APPROVED_REVIEWER)
            ->orderBy('id', 'desc')->get();
         return $drdrs;
    }

    
    /*
     * Display a listing of the approved forms.
     *
     * @return \Illuminate\Http\Response
     */

    public function approvedForms()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer', 'company')
            ->where('approver_id',$id)
            ->where('status', '!=' ,StatusType::APPROVED_REVIEWER)
            ->orderBy('id', 'desc')->get();
         return $drdrs;
    }

    /**
     * Display a listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */

    public function submitted()
    {
        $drdrs = Drdr::with('reviewer', 'approver')->orderBy('id', 'desc')->where('requester_id', Auth::user()->id)->get();
        return $drdrs;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('drdr.form',['location' => 'Document Review & Document Request']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'=> 'required',
            'document_title' => 'required',
            'rev_number' => 'required',
            'company_id' => 'required',
            'reviewer_id' => 'required',
            'reason_request' => 'required',
            'attachments' => 'required',
            'effective_date' => 'required'
        ]);
        
        $drdr = new Drdr;
        $carbon = new Carbon();
        $path = '';

        $drdr->request_type = $request->input('type');
        $drdr->document_title = $request->input('document_title');
        $drdr->reason_request = $request->input('reason_request');
        $drdr->rev_number = $request->input('rev_number');
        $drdr->company_id = $request->input('company_id');
        $drdr->date_request =  $carbon::now();
        $drdr->effective_date = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('effective_date'));
        $drdr->requester_id = Auth::user()->id;
        $drdr->reviewer_id = $request->input('reviewer_id');
        $drdr->status = StatusType::SUBMITTED;

        $reviewer = User::findOrFail($request->input('reviewer_id'));
        $reviewer->notify(new RequesterSubmitDrdr($drdr, Auth::user()));

        if($drdr->save()){
            $attachments = $request->file('attachments');   
            foreach($attachments as $attachment){
                $filename = $attachment->getClientOriginalName();
                $path = $attachment->store('drdr');
                $role = 'requester';

                $uploadedFile = $this->uploadFiles(Auth::user()->id, $drdr->id, $path,$role,$filename);
            } 
        
            return ['redirect' => route('drdr')];
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
        $drdr = Drdr::findOrFail($id);
        if($drdr->status == StatusType::SUBMITTED){
            return view('drdr.review',['location' => 'Document Review & Document Request']);
        } return redirect()->back();
    }

    /**
    * Get the specified drdr by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function data($id)
    {
        $drdr = Drdr::with(['company', 'requester', 'approver', 'reviewer', 'distributed'])
                ->where('id', $id)
                ->get();

        return $drdr;
    }

    /**
    * Display the approving form of drdr.
    *
    * @return \Illuminate\Http\Response
    */
    public function showApprovedForm($id)
    {
        $drdr = Drdr::findOrFail($id);
        if($drdr->status == StatusType::APPROVED_REVIEWER){
            return view('drdr.approve-form',['location' => 'Document Review & Document Request']);
        } return redirect()->back();
    }

    /**
    * Display the details of apporved drdr. 
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsDrdr($drdr_id)
    {
        return view('drdr.view',['location' => 'Document Review & Document Request']);
    }

    /**
    * Display the edit form.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $drdr = Drdr::findOrfail($id);
     
        if($drdr->status == StatusType::SUBMITTED){
            return view('drdr.edit',['location' => 'Document Review & Document Request']);
        } return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Drdr $drdr)
    {
        $request->validate([
            'type'=> 'required',
            'document_title' => 'required',
            'rev_number' => 'required',
            'company_id' => 'required',
            'reviewer_id' => 'required',
            'reason_request' => 'required',
            'company_id' => 'required',
        ]);

        $carbon = new Carbon();
        
        $drdr->request_type = $request->input('type');
        $drdr->document_title = $request->input('document_title');
        $drdr->reason_request = $request->input('reason_request');
        $drdr->rev_number = $request->input('rev_number');
        $drdr->company_id = $request->input('company_id');
        $drdr->requester_id = Auth::user()->id;
        $drdr->reviewer_id = $request->input('reviewer_id');
        $drdr->status = StatusType::SUBMITTED;
        if($drdr->save()){
            return ['redirect' => route('drdr')];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $drdr = Drdr::findOrFail($id);
        if($drdr->delete()){
            return $drdr;
        }
    }
    
    /**
     * Get specific reviewer per company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getCompanyReviewers($company_id){
        $reviewer = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::REVIEWER);
        })->whereHas('companies', function($q) use ($company_id) {
            $q->where('company_id',$company_id);
        })->get();

        return $reviewer;
    }

     /**
     * Get specific approver per company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approver($company_id)
    {
        $approver = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::APPROVER);
        })->whereHas('companies', function($q) use ($company_id) {
            $q->where('company_id',$company_id);
        })->get();

        return $approver;
    }
    
    /**
     * Reviewer approved or disapproved drdr
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function reviewed(Request $request)
    {
        
        $drdr = Drdr::findOrFail($request->input('id'));
        $carbon = new Carbon();


        if($request->input('status') == 1){

            $request->validate([
                'consider_documents' => 'required',
                'approver' => 'required',
                'attachments' => 'required',
                'status' => 'required',
                'id' => 'required',
                'remarks' => 'required',
            ]);

            $drdr->status = StatusType::APPROVED_REVIEWER;
            $drdr->reviewed_date = $carbon::now();
            $drdr->consider_documents = $request->input('consider_documents');
            $drdr->remarks = $request->input('remarks');
            $drdr->approver_id = $request->input('approver');
            
            $requester = User::findOrFail($drdr->requester_id);
            $approver = User::findOrFail($request->input('approver'));
            $approver->notify(new ReviewerReviewedDrdr($drdr, $requester ,Auth::user()));
    
            if($drdr->save()){
                $attachments = $request->file('attachments');   
                foreach($attachments as $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = $attachment->store('drdr');
                    $role = 'reviewer';
    
                    $uploadedFile = $this->uploadFiles(Auth::user()->id, $drdr->id, $path, $role,$filename);
                } 
            
                return ['redirect' => route('drdr')];
            }
        }else{

            $request->validate([
                'status' => 'required',
                'id' => 'required',
                'remarks' => 'required',
            ]);

            $drdr->status = StatusType::DISAPPROVED_REVIEWER;
            $drdr->disapproved_date =  $carbon::now();
            $drdr->remarks = $request->input('remarks');

            $requester = User::findOrFail($drdr->requester_id);
            // Notification of disapproved drdr to requester
            $requester->notify(new ReviewerDisapprovedDrdr($drdr,Auth::user()));

            if($drdr->save()){
                return ['redirect' => route('drdr')];
            }
        }

    }

    /**
     * Approver approved or disapproved drdr
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function approved(Request $request)
    {
        
        $carbon = new Carbon();
        $drdr = Drdr::findOrFail($request->input('id'));

        if($request->input('status') == 1){
            $request->validate([
                'remarks' => 'required',
                'attachments' => 'required',
                'copy_number' => 'required',
                'copy_holder' => 'required',
                'status' => 'required',
                'id' => 'required'
            ]);
    
            $drdr->status = StatusType::APPROVED_APPROVER ;
            $drdr->approved_date = $carbon::now();
            $drdr->copy_number = $request->input('copy_number');
            $drdr->copy_holder = $request->input('copy_holder');
            $drdr->remarks = $request->input('remarks');
            $drdr->effective_date =  Carbon::parse($request->input('effective_date'))->format('Y-m-d');
        
            $company_id = $drdr->company_id;
            $mr = User::whereHas('roles', function($q) {
                $q->where('role_id', RolesType::MR);
            })->whereHas('companies', function($q) use ($company_id) {
                $q->where('company_id',$company_id);
            })->get();
            
            $requester = User::findOrFail($drdr->requester_id);

            \Notification::send($mr, new ApproverNotifyMrDrdr($drdr, $requester));
    
            if($drdr->save()){
                $attachments = $request->file('attachments');   
                foreach($attachments as $attachment){
                    $filename = $attachment->getClientOriginalName();
                    $path = $attachment->store('drdr');
                    $role = 'approver';
    
                    $uploadedFile = $this->uploadFiles(Auth::user()->id, $drdr->id, $path, $role,$filename);
                } 
            
                return ['redirect' => route('drdr')];
            }

        }else{

            $request->validate([
                'remarks' => 'required',
                'status' => 'required',
                'id' => 'required'
            ]);

            $drdr->status = StatusType::DISAPPROVED_APPROVER;
            $drdr->disapproved_date =  $carbon::now();
            $drdr->remarks = $request->input('remarks');

            $requester = User::findOrFail($drdr->requester_id);
            // Notification of disapproved drdr to requester
            $requester->notify(new ApproverDisapprovedDrdr($drdr,Auth::user()));

            if($drdr->save()){
                return ['redirect' => route('drdr')];
            }
        }

    }

    /**
     *  Return total count of drdr
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function countDrdr()
    {
        if(Auth::user()->hasRole('administrator')){
            $drdrs =  Drdr::count();
        }else{
            $drdrs =  Drdr::whereIn('company_id', Auth::user()->companies->pluck('id'))->get()->count();
        }
        return $drdrs;
    }

   /**
    * Display the drdr page for admin.
    *
    * @return \Illuminate\Http\Response
    */
    public function drdrAdminPage()
    {
        return view('admin.admin-drdr',['location' => 'Document Review & Document Request']);
    }

    /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllDrdrs()
    {
        if(Auth::user()->hasRole('administrator')){
            $drdrs = Drdr::with(['reviewer', 'approver', 'company'])->orderBy('id', 'desc')->get();
        }else{
            $drdrs = Drdr::with(['reviewer', 'approver', 'company'])
            ->whereIn('company_id', Auth::user()->companies->pluck('id'))->orderBy('id', 'desc')->get();
        }

        return $drdrs;
    }

    /**
     * Return drdr details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function drdrDetails($id)
    {
        $location = 'Document Review & Document Request';
        return view('admin.admin-drdr-details', compact('id', 'location'));
    }
    
    /**
     * Generate drdr pdf to download or print
     *
     * @return \Illuminate\Http\Response
     */

    public function drdrPdf($id)
    {
        $drdr = $this->data($id);
        $pdf = PDF::loadView('admin.admin-drdr-pdf', ['drdr' => $drdr]);

        return $pdf->stream('drdr.pdf');
    }

    /**
     * Uploading files for drdr
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles($userId,$drdrId,$path,$role,$filename)
    {
        $uploadedFile = new UploadedFile;
        $uploadedFile->user_id = $userId;
        $uploadedFile->form_id = $drdrId;
        $uploadedFile->role = $role;
        $uploadedFile->file_path =  $path;
        $uploadedFile->file_name = $filename;
        $uploadedFile->model = 'App\Drdr';
        $uploadedFile->save();
    }

    /**
     * Get requester upload files of drdr 
     *
     * @return \Illuminate\Http\Response
     */

    public function getUploadedFilesRequester($drdrId, $requesterId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $requesterId)
        ->where('form_id', $drdrId)
        ->where('role', 'requester')
        ->where('model', 'App\Drdr')
        ->get();

        return $uploadedFile;
    }

    /**
     * Get reviewer upload files of drdr 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUploadedFilesReviewer($drdrId,$reviewerId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $reviewerId)
            ->where('form_id', $drdrId)
            ->where('role', 'reviewer')
            ->where('model', 'App\Drdr')
            ->get();

        return $uploadedFile;
    }

     /**
     * Get approver upload files of drdr 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUploadedFilesApprover($drdrId,$approverId)
    {
        $uploadedFile =  UploadedFile::where('user_id', $approverId)
            ->where('form_id', $drdrId)
            ->where('role', 'approver')
            ->where('model', 'App\Drdr')
            ->get();

        return $uploadedFile;
    }

     /**
     * Download upload files/attachments of drdr 
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
     *  Search DRDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generate(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        
        $drdrs = Drdr::with(['reviewer', 'approver', 'company'])
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $drdrs;
    }

    /**
     * Mark DRDR as distributed
     *
     * @return \Illuminate\Http\Response
     */

    public function distributed(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $carbon = new Carbon();
        $drdr = Drdr::findOrFail($request->id);
        $approver = User::findOrFail($drdr->approver_id);
        $reviewer = User::findOrFail($drdr->reviewer_id);
        $requester = User::findOrFail($drdr->requester_id);

        $emails = [$approver, $reviewer, $requester];

        $drdr->distributed_id = Auth::user()->id;
        $drdr->status = StatusType::MARK_AS_DISTRIBUTED;
        $drdr->distributed_date = $carbon::now();
        if($drdr->save()){
            $requester = User::findOrFail($drdr->requester_id);
            \Notification::send($emails , new MrMarkAsDistributedDrdr($drdr, $requester, Auth::user()));
            return ['redirect' => route('admin.drdrs')];
        }
    }

    public function emailScheduling(){
        
        $drdrSubmitted = Drdr::where('status', StatusType::SUBMITTED)->whereDate('effective_date','=', Carbon::today()->subDays(-1)->toDateTimeString())->get();
        $drdrReviewed = Drdr::where('status', StatusType::APPROVED_REVIEWER)->whereDate('effective_date','=', Carbon::today()->subDays(-1)->toDateTimeString())->get();
        if($drdrSubmitted){
            $reviewerId = $drdrSubmitted->pluck('reviewer_id');
            foreach($reviewerId as $key => $id){
                $reviewer = User::findOrFail($id);
                $reviewer->notify(new SchedulingNotifyReviewerDrdr($drdrSubmitted[$key]));
            }
        }

        if($drdrReviewed){
            $approverId = $drdrReviewed->pluck('approver_id');
            foreach($approverId as $key => $id){
                $approver = User::findOrFail($id);
                $approver->notify(new SchedulingNotifyApproverDrdr($drdrReviewed[$key]));
            }
        }
    }
}
