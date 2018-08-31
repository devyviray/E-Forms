<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ncn;
use App\User;
use App\Types\StatusType;
use Carbon\Carbon;
use Auth;
use PDF;
use App\Notifications\RequesterSubmitNcn;

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
            ->where('status', StatusType::APPROVED_APPROVER)
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
        return view('ncn.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $ncn = new Ncn;
        $carbon = new Carbon();

        $ncn->requester_id = Auth::user()->id;
        $ncn->company_id = $request->input('company_id');
        $ncn->department_id = $request->input('department_id');
        $ncn->approver_id = $request->input('approver_id');
        $ncn->non_conformity_types = $request->input('non_conformity_types');
        $ncn->notification_number = $request->input('notification_number');
        $ncn->recurrence_number = $request->input('recurrence_number');
        $ncn->issuance_date = $carbon::parse($request->input('issuance_date'))->toDateTimeString();
        $ncn->request_date = $carbon::now();
        // $ncn->approved_date = '';
        // $ncn->disapproved_date = '';
        $ncn->non_conformity_details = $request->input('non_conformity_details');
        $ncn->attached_files = 'sasasasasassa';
        $ncn->status = StatusType::SUBMITTED;
        // $ncn->remarks = '';

        $user = User::findOrFail($request->input('approver_id'));
        $user->notify(new RequesterSubmitNcn($ncn));

        if($ncn->save()){
            return ['redirect' => route('ncn')]; 
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
            $q->where('role_id', 2);  // approver
        })->whereHas('companies', function($q) use ($company){
                $q->where('company_id',$company);
        })->where('department_id', $department)->get();
        return $approvers;
    }

    /**
     * Count all submitted ncn
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function countNcn()
    {
        $ncns = Ncn::count();
        return $ncns;
    }
    
    /**
    * Display the ncn page for admin.
    *
    * @return \Illuminate\Http\Response
    */
    public function ncnAdminPage()
    {
        return view('admin.admin-ncn');
    }

    /**
     * Display all the listing of the submitted forms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllNcns()
    {
        $ncns = Ncn::with(['requester', 'approver', 'company'])->get();

        return $ncns;
    }

    /**
     * Return ddr details page for admin
     *
     * @return \Illuminate\Http\Response
     */
    public function ncnDetails($id)
    {
        return view('admin.admin-ncn-details', ['id' => $id]);
    }

    /**
     * Generate drdr pdf to download or print
     *
     * @return \Illuminate\Http\Response
     */

    public function ncnPdf($id)
    {
        $pdf = PDF::loadView('admin.admin-ncn-pdf');

        return $pdf->stream('ncn.pdf');
    }

     /**
     * Search ncn by category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function category($category)
    {
        $id = User::findOrFail(Auth::user()->id);

        switch  ($category)
        {
            case "all":
                $ncns = Ncn::with('requester')
                ->where('approver_id', Auth::user()->id)
                ->orderBy('id', 'desc')->get();
        
                return $ncns;

                break;
            
            case "pending":
                $ncns = Ncn::with('requester')
                ->where('approver_id', Auth::user()->id)
                ->where('status', StatusType::SUBMITTED)
                ->orderBy('id', 'desc')->get();
        
                return $ncns;

                break;

            case "approved":
                $ncns = Ncn::with('requester')
                ->where('approver_id', Auth::user()->id)
                ->where('status', StatusType::APPROVED_APPROVER)
                ->orderBy('id', 'desc')->get();
        
                return $ncns;

                break;
        }
    }
}
