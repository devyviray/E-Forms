<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ddr;
use App\User;
use App\DdrformsList;
use App\Types\StatusType;
use App\Types\RolesType;
use Auth;
use PDF;
use Carbon\Carbon;
use App\Notifications\DdrRequestApproval;
use App\Notifications\DdrApproved;
use App\Notifications\DdrRequestVerified;
use App\Notifications\DdrRequestDisapproved;

class DdrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $ddrs = Ddr::with('requester')
            ->where('approver_id', $id)
            ->where('status', StatusType::SUBMITTED)
            ->orderBy('id','desc')->get();

        return $ddrs;
    }

    /**
     * Display a listing of submitted DDR.
     *
     * @return \Illuminate\Http\Response
     */
    public function submitted()
    {
        $ddrs = Ddr::with('approver')->orderBy('id', 'desc')->where('requester_id', Auth::user()->id)->get();
        return $ddrs;
    }
    
    /**
     * Display a listing of approved DDR.
     *
     * @return \Illuminate\Http\Response
     */
    public function approvedForms()
    {
        $ddrs = Ddr::with('requester')
                ->where('approver_id', Auth::user()->id)
                ->where('status', '!=' , StatusType::SUBMITTED)
                ->orderBy('id', 'desc')->get();
        return $ddrs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ddr.form', ['location' => 'Document Distribution Request']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = $request->input('reason') == 3 ? 'required' : '';
        $validator = $request->validate([
            'company' => 'required',
            'company_location' => 'required',
            'department' => 'required' ,
            'reason' => 'required',
            'others' => $rules,
            'date_needed' => 'required',
            "ddrlists.*.document_title"  => "required",
            "ddrlists.*.control_code"  => "required",
            "ddrlists.*.rev_number"  => "required",
            "ddrlists.*.copy_number"  => "required",
            "ddrlists.*.copy_holder"  => "required",
            'approver' => 'required' 
        ]);

        $ddr = new Ddr;
        $carbon = new Carbon();
 
        $ddr->company_id = $request->input('company');
        $ddr->department_id = $request->input('department');
        $ddr->reason_of_distribution = $request->input('reason');
        $ddr->date_needed = Carbon::parse($request->input('date_needed'));
        $ddr->date_request = $carbon::now();
        $ddr->requester_id  = Auth::user()->id;
        $ddr->approver_id = $request->input('approver');
        $ddr->others = $request->input('reason') == 3 ? $request->input('others') : '';
        $ddr->status = StatusType::SUBMITTED;

        if($ddr->save()){
            $approver = User::findOrFail($request->input('approver'));
            $approver->notify(new DdrRequestApproval($ddr, Auth::user()));

            $ddrFormsLists = $request->input('ddrlists');
            foreach($ddrFormsLists as $list){
                $ddrFormsList = new DdrformsList;

                $ddrFormsList->form_id = $ddr->id;
                $ddrFormsList->document_title = $list['document_title'];
                $ddrFormsList->control_code = $list['control_code'];
                $ddrFormsList->rev_number = $list['rev_number'];
                $ddrFormsList->copy_number = $list['copy_number'];
                $ddrFormsList->copy_holder = $list['copy_holder'];
                $ddrFormsList->save();

            }
            return ['redirect' => route('ddr')];
        }
    }

    /**
    * Display approve page of ddr
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $ddr = Ddr::findOrFail($id);
        if($ddr->status != StatusType::SUBMITTED){
            return redirect()->back();
        }  return view('ddr.approved', ['location' => 'Document Distribution Request']);
    }

    /**
    * Get the specified ddr by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function data($id)
    {
        $ddr = Ddr::with(['company', 'department', 'requester', 'approver', 'ddrLists', 'distributed'])
                ->where('id', $id)
                ->get();

        return $ddr;
    }
    
    /**
    * Approve specific DDR
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function approved(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);
        
        $carbon = new Carbon();
        $ddr = Ddr::findOrFail($request->input('id'));

        if($request->input('status') == 1) {

            $ddr->status = StatusType::APPROVED_APPROVER;
            $ddr->remarks = $request->input('remarks');
            $ddr->approved_date = $carbon::now();
            $ddr->save();
            
            $company_id = $ddr->company_id;
            $requester = User::findOrFail($ddr->requester_id);
            $mr = User::whereHas('roles', function($q) {
                $q->where('role_id', RolesType::MR);
            })->whereHas('companies', function($q) use ($company_id) {
                $q->where('company_id',$company_id);
            })->get();

            \Notification::send($mr, new DdrApproved($ddr,$requester, Auth::user()));
        }else {

            $ddr->status = StatusType::DISAPPROVED_APPROVER;
            $ddr->remarks = $request->input('remarks');
            $ddr->dispproved_date = $carbon::now();
            $ddr->save();
            
            $requester = User::findOrFail($ddr->requester_id); 
            // Email sending to requester of disapproved ddr
            $requester->notify(new DdrRequestDisapproved($ddr,$requester, Auth::user()));
        }
        


        return ['redirect' => route('ddr')];
    }

    /**
    * View the details of ddr.
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsDdr($ddr_id)
    {
        return view('ddr.view', ['location' => 'Document Distribution Request']);
    }

    /**
    * Display the edit form.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $ddr = Ddr::findOrfail($id);
     
        if($ddr->status == StatusType::SUBMITTED && $ddr->requester_id == Auth::user()->id){
            return view('ddr.edit', ['location' => 'Document Distribution Request']);
        } return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Ddr $ddr)
    {
        $validator = $request->validate([
            'company' => 'required',
            'company_location' => 'required',
            'approver' => 'required' ,
            'department' => 'required' ,
            'reason' => 'required',
            'date_needed' => 'required',
            "ddrlists.*.document_title"  => "required",
            "ddrlists.*.control_code"  => "required",
            "ddrlists.*.rev_number"  => "required",
            "ddrlists.*.copy_number"  => "required",
            "ddrlists.*.copy_holder"  => "required",
        ]);
        
        $request->input('reason') == 3 ? $request->validate(['others' => 'required']) : '' ;

        $ddr->company_id = $request->input('company');
        $ddr->department_id = $request->input('department');
        $ddr->reason_of_distribution = $request->input('reason');
        $ddr->others = $request->input('reason') == 3 ? $request->input('others') : '';
        $ddr->date_needed = Carbon::parse($request->input('date_needed'));
        $ddr->approver_id = $request->input('approver');
        $ddr->status = StatusType::SUBMITTED;

        if($ddr->save()){
            $ddrFormsLists = $request->input('ddrlists');
            foreach($ddrFormsLists as $list){

                isset($list['id']) ? $ddrFormsList = DdrformsList::findOrFail($list['id']) :  $ddrFormsList = new DdrformsList;

                $ddrFormsList->form_id = $ddr->id;
                $ddrFormsList->document_title = $list['document_title'];
                $ddrFormsList->control_code = $list['control_code'];
                $ddrFormsList->rev_number = $list['rev_number'];
                $ddrFormsList->copy_number = $list['copy_number'];
                $ddrFormsList->copy_holder = $list['copy_holder'];
                $ddrFormsList->save();

            }
            return ['redirect' => route('ddr')];
        }
    }

     /**
     * Admin Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function adminUpdate(Request $request, Ddr $ddr)
    {
        $validator = $request->validate([
            "ddrlists.*.document_title"  => "required",
            "ddrlists.*.control_code"  => "required",
            "ddrlists.*.rev_number"  => "required",
            "ddrlists.*.copy_number"  => "required",
            "ddrlists.*.copy_holder"  => "required",
        ]);


        $ddrFormsLists = $request->input('ddrlists');
        foreach($ddrFormsLists as $list){

            $ddrFormsList = DdrformsList::findOrFail($list['id']);

            $ddrFormsList->form_id = $ddr->id;
            $ddrFormsList->document_title = $list['document_title'];
            $ddrFormsList->control_code = $list['control_code'];
            $ddrFormsList->rev_number = $list['rev_number'];
            $ddrFormsList->copy_number = $list['copy_number'];
            $ddrFormsList->copy_holder = $list['copy_holder'];
            $ddrFormsList->save();

        }
        return ['redirect' => route('admin.ddrs')];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteDdrList($id)
    {
        $DdrformsList = DdrformsList::findOrFail($id);

        if($DdrformsList->delete()){
            return $DdrformsList;
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
        $ddr = Ddr::findOrFail($id);

        if($ddr->delete()){
            return $ddr;
        }
    }

    /**
     * Get approvers base on company
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getCompanyApprovers($company_id){
        $reviewer_user = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::APPROVER);
        })->whereHas('companies', function($q) use ($company_id) {
            $q->where('company_id',$company_id);
        })->get();

        return $reviewer_user;
    }

    /**
     * Count total submitted ddr
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function countDdr()
    {
        if(Auth::user()->hasRole('administrator')){
            $ddr = Ddr::count();
        }else{
            $ddr = Ddr::whereIn('company_id', Auth::user()->companies->pluck('id'))->get()->count();
        }
        return $ddr;
    }
    
    /**
    * Display the ddr page for admin.
    *
    * @return \Illuminate\Http\Response
    */

    public function ddrAdminPage()
    {
        return view('admin.admin-ddr',['location' => 'Document Distribution Request']);
    }

     /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllDdrs()
    {
        if(Auth::user()->hasRole('administrator')){
            $ddrs = Ddr::with(['requester', 'approver', 'company', 'ddrLists'])->orderBy('id','desc')->get();
        }else{
            $ddrs = Ddr::with(['requester', 'approver', 'company', 'ddrLists'])
            ->whereIn('company_id', Auth::user()->companies->pluck('id'))->orderBy('id','desc')->get();
        }

        return $ddrs;
    }

     /**
     * Return ddr details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function ddrDetails($id)
    {
        $location = 'Document Distribution Request';
        return view('admin.admin-ddr-details', compact('id', 'location'));
    }

    /**
     * Generate drdr pdf to download or print
     *
     * @return \Illuminate\Http\Response
     */

    public function ddrPdf($id)
    {
        $ddr = $this->data($id);
        $pdf = PDF::loadView('admin.admin-ddr-pdf', ['ddr' => $ddr]);

        return $pdf->stream('ddr.pdf');

        // return view('admin.admin-ddr-pdf', ['ddr' => $ddr]);
    }

    /**
     *  Search DDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generate(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);

        $ddrs = Ddr::with(['requester', 'approver', 'company'])
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }

    /**
     *  Search submitted DDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generateSubmitted(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        
        $ddrs = Ddr::with(['approver', 'company'])
                ->where('requester_id', Auth::user()->id)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }

    /**
     *  Search approved DDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generateApproved(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        $ddrs = Ddr::with(['requester','approver', 'company'])
                ->where('approver_id', Auth::user()->id)
                ->where('status', '!=' , StatusType::SUBMITTED)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }

    /**
     *  Search pending approval DDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generatePendingApproval(Request $request)
    {
        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required|after_or_equal:startDate'
        ]);
        $ddrs = Ddr::with(['requester', 'approver', 'company'])
                ->where('approver_id',Auth::user()->id)
                ->where('status', StatusType::SUBMITTED)
                ->whereDate('date_request', '>=',  Carbon::parse($request->input('startDate'))->format('Y-m-d'))
                ->whereDate('date_request' ,'<=', Carbon::parse($request->input('endDate'))->format('Y-m-d'))
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }

    /**
     * Mark DDR as distributed
     *
     * @return \Illuminate\Http\Response
     */

    public function distributed(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $carbon = new Carbon();
        $ddr = Ddr::findOrFail($request->id);
        $approver = User::findOrFail($ddr->approver_id);
        $requester = User::findOrFail($ddr->requester_id);

        $emails = [$approver, $requester];

        $ddr->distributed_id = Auth::user()->id;
        $ddr->status = StatusType::MARK_AS_DISTRIBUTED;
        $ddr->distributed_date = $carbon::now();
        if($ddr->save()){
            // \Notification::send($emails , new DdrRequestVerified($ddr,$requester,Auth::user()));
            $requester->notify(new DdrRequestVerified($ddr,$requester, Auth::user()));
            
            return ['redirect' => route('admin.ddrs')];
        }
    }
}
