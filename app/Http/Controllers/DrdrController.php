<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Drdr;
use App\User;
use App\Types\RequestType;
use App\Types\StatusType;
use Auth;
use Carbon\Carbon;
use App\Notifications\RequesterSubmitDrdr;

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
            ->where('status', StatusType::APPROVED_REVIEWER)
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

    
    /**
     * Display a listing of the approved forms.
     *
     * @return \Illuminate\Http\Response
     */

    public function approvedForms()
    {
        $id = Auth::user()->id;
        $drdrs = Drdr::with('reviewer', 'company')
            ->where('approver_id',$id)
            ->where('status', StatusType::APPROVED_APPROVER)
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
        return view('drdr.form');
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
            // 'approver_id' => 'required',
            // 'company_id' => 'required',
            // 'file' => 'required'
        ]);

        $drdr = new Drdr;
        $carbon = new Carbon();
        $path = '';
        
        $attachments = $request->file('attachments');   
        foreach($attachments as $attachment){
            $path = $attachment->store('drdr');
        }   
        $drdr->request_type = $request->input('type');
        $drdr->document_title = $request->input('document_title');
        $drdr->reason_request = $request->input('reason_request');
        $drdr->rev_number = $request->input('rev_number');
        $drdr->company_id = $request->input('company_id');
        $drdr->attached_file = $path;
        $drdr->date_request =  $carbon::now();
        $drdr->effective_date = $carbon::now();
        $drdr->requester_id = Auth::user()->id;
        $drdr->reviewer_id = $request->input('reviewer_id');
        $drdr->reviewed_date =  $carbon::now();
        $drdr->remarks = 'sadadsadada';
        $drdr->status = StatusType::SUBMITTED;

        $user = User::findOrFail($request->input('reviewer_id'));
        $user->notify(new RequesterSubmitDrdr($drdr));

        if($drdr->save()){
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
        return view('drdr.view');
    }

    public function data($id)
    {
        $drdr = Drdr::with(['company', 'requester'])
                ->where('id', $id)
                ->get();

        return $drdr;
    }

    /**
    * Display the approving form of drdr.
    *
    * @return \Illuminate\Http\Response
    */
    public function showApprovedForm()
    {
        return view('drdr.approve-form');
    }

    /**
    * Display the details of apporved drdr.
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsDrdr($drdr_id)
    {
        return view('drdr.approved-details');
    }

    /**
    * Display the edit form.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit()
    {
        return view('drdr.edit');
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
            // 'attachments' => 'required',
            'company_id' => 'required',
        ]);

        $carbon = new Carbon();
        $path = '';
        
        // $attachments = $request->file('attachments');   
        // foreach($attachments as $attachment){
        //     $path = $attachment->store('drdr');
        $drdr->request_type = $request->input('type');
        $drdr->document_title = $request->input('document_title');
        $drdr->reason_request = $request->input('reason_request');
        $drdr->rev_number = $request->input('rev_number');
        $drdr->company_id = $request->input('company_id');
        // $drdr->attached_file = $path;
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
            $q->where('role_id', 3);  // reviewer
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
            $q->where('role_id', 2);  // reviewer
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
        
        $status = $request->input('status') == 1 ? StatusType::APPROVED_REVIEWER : StatusType::DISAPPROVED_REVIEWER;
        $drdr->status = $status;
        $drdr->remarks = $request->input('remarks');
        $drdr->approver_id = $request->input('approver_id');
        $drdr->save();

        // $approver = User::findOrFail($request->input('approver_id'));
        // $approver->notify(new RequesterSubmitDrdr($drdr));

        return ['redirect' => route('drdr')];
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
        $requester = User::findOrFail($drdr->requester_id);
        
        $status = $request->input('status') == 1 ? StatusType::APPROVED_APPROVER : StatusType::DISAPPROVED_APPROVER;
        $drdr->status = $status;
        $drdr->remarks = $request->input('remarks');
        // $drdr->effective_date = $carbon::parse($request->input('effective_date'))->toDateTimeString();
        $drdr->save();
        
        // $requester = User::findOrFail($drdr->requester_id);
        // $requester->notify(new RequesterSubmitDrdr($drdr));


        return ['redirect' => route('drdr')];
    }

    /**
     *  Return total count of drdr
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function countDrdr()
    {
        $drdrs =  Drdr::count();
        return $drdrs;
    }

   /**
    * Display the drdr page for admin.
    *
    * @return \Illuminate\Http\Response
    */
    public function drdrAdminPage()
    {
        return view('admin.admin-drdr');
    }

    /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllDrdrs()
    {
        $drdrs = Drdr::with(['reviewer', 'approver', 'company'])->get();

        return $drdrs;
    }

    /**
     * Search drdr by category     
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category($category)
    {
        $id = Auth::user()->id;
        switch ($category)
        {   
            case "all":
                $drdrs = Drdr::with('reviewer', 'approver')
                    ->where('reviewer_id',$id)
                    ->orWhere('approver_id', $id)
                    ->orderBy('id', 'desc')->get();

                return $drdrs;
                
                break;
            case "pending":
                $drdrs = Drdr::with('reviewer', 'approver')
                    ->where('status', StatusType::SUBMITTED)
                    ->where(function ($query) use ($id){
                        $query->where('reviewer_id', $id)
                              ->orWhere('approver_id', $id);
                    })
                    ->orderBy('id', 'desc')->get();
                    
                return $drdrs;

                break;
            case "approved":
                $drdrs = Drdr::with('reviewer', 'approver')
                    ->whereIn('status', [StatusType::APPROVED_REVIEWER, StatusType::APPROVED_APPROVER])
                    ->where(function ($query) use ($id){
                            $query->where('reviewer_id', $id)
                                ->orWhere('approver_id', $id);
                    })
                    ->orderBy('id', 'desc')->get();
                    
                return $drdrs;

                break;
        }
    }
}
