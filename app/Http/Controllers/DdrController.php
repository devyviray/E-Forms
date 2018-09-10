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
use App\Notifications\RequesterSubmitDdr;
use App\Notifications\ApproverNotifyMrDdr;

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
                ->where('status', StatusType::APPROVED_APPROVER)
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
        return view('ddr.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validator = $request->validate([
            'company_id' => 'required',
            'department_id' => 'required' ,
            'reason' => 'required',
            'date_needed' => 'required',
            "ddrlists.*.document_title"  => "required",
            "ddrlists.*.control_code"  => "required",
            "ddrlists.*.rev_number"  => "required",
            "ddrlists.*.copy_number"  => "required",
            "ddrlists.*.copy_holder"  => "required",
            'approver_id' => 'required' 
        ]);
        $request->input('reason') == 3 ? $request->validate(['others' => 'required']) : '' ;

        $ddr = new Ddr;
        $carbon = new Carbon();
 
        $ddr->company_id = $request->input('company_id');
        $ddr->department_id = $request->input('department_id');
        $ddr->reason_of_distribution = $request->input('reason');
        $ddr->date_needed = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('date_needed'));
        $ddr->date_request = $carbon::now();
        $ddr->requester_id  = Auth::user()->id;
        $ddr->approver_id = $request->input('approver_id');
        $ddr->others = $request->input('reason') == 3 ? $request->input('others') : '';
        $ddr->status = StatusType::SUBMITTED;

        $user = User::findOrFail($request->input('approver_id'));
        $user->notify(new RequesterSubmitDdr($ddr));

        if($ddr->save()){
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
        return view('ddr.approved');
    }

    /**
    * Get the specified ddr by id.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function data($id)
    {
        $ddr = Ddr::with(['company', 'department', 'requester', 'approver', 'ddrLists'])
                ->where('id', $id)
                ->get();

        return $ddr;
    }

    public function approved(Request $request)
    {
        $carbon = new Carbon();
        $ddr = Ddr::findOrFail($request->input('id'));
        
        $status = $request->input('status') == 1 ? StatusType::APPROVED_APPROVER : StatusType::DISAPPROVED_APPROVER;
        $ddr->status = $status;
        $ddr->remarks = $request->input('remarks');
        $ddr->effective_date = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('effective_date'));
        $ddr->save();
        
        $company_id = $ddr->company_id;
        $mr = User::whereHas('roles', function($q) {
            $q->where('role_id', RolesType::MR);
        })->whereHas('companies', function($q) use ($company_id) {
            $q->where('company_id',$company_id);
        })->get();

        \Notification::send($mr, new ApproverNotifyMrDdr($ddr));


        return ['redirect' => route('ddr')];
    }

    /**
    * View the details of ddr.
    *
    * @return \Illuminate\Http\Response     
    */
    public function showDetailsDdr($ddr_id)
    {
        return view('ddr.view');
    }

    /**
    * Display the edit form.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $ddr = Ddr::findOrfail($id);
     
        if($ddr->status == StatusType::SUBMITTED){
            return view('ddr.edit');
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
        $ddr->date_needed = \DateTime::createFromFormat('D M d Y H:i:s e+', $request->input('date_needed'));
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
        $ddr = Ddr::count();
        return $ddr;
    }
    
    /**
    * Display the ddr page for admin.
    *
    * @return \Illuminate\Http\Response
    */

    public function ddrAdminPage()
    {
        return view('admin.admin-ddr');
    }

     /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllDdrs()
    {
        $drdrs = Ddr::with(['requester', 'approver', 'company', 'ddrLists'])
            ->orderBy('id','desc')->get();

        return $drdrs;
    }

     /**
     * Return ddr details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function ddrDetails($id)
    {
        return view('admin.admin-ddr-details', ['id' => $id]);
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
    }

    /**
     *  Search DDR by start date and end date
     *
     * @return \Illuminate\Http\Response
     */

    public function generate(Request $request, $startDate,$endDate)
    {
        $from = \DateTime::createFromFormat('D M d Y H:i:s e+', $startDate);
        $to =  \DateTime::createFromFormat('D M d Y H:i:s e+', $endDate);
        $ddrs = Ddr::with(['requester', 'approver', 'company'])
                ->where('date_request', '>=', $from)
                ->where('date_request' ,'<=', $to)
                ->orderBy('id', 'desc')->get();

        return $ddrs;
    }

    /**
     * Search DDR by category
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
                $ddrs = Ddr::with('requester')
                ->where('approver_id', $id)
                ->orderBy('id','desc')->get();

                return $ddrs;

                break;
            case "pending":
                $ddrs = Ddr::with('requester')
                ->where('approver_id', $id)
                ->where('status', StatusType::SUBMITTED)
                ->orderBy('id','desc')->get();

                return $ddrs;

                break;
            case "approved":
                $ddrs = Ddr::with('requester')
                ->where('approver_id', $id)
                ->where('status', StatusType::APPROVED_APPROVER)
                ->orderBy('id','desc')->get();

                return $ddrs;
                
                break;
        }
    }
}
