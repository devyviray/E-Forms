<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ddr;
use App\User;
use App\Types\StatusType;
use Auth;
use PDF;
use Carbon\Carbon;
use App\Notifications\RequesterSubmitDdr;

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
            'document_title' => 'required',
            'control_code' => 'required',
            'rev_number' => 'required',
            'copy_holder' => 'required',
            'approver_id' => 'required' 
        ]);

        $ddr = new Ddr;
        $carbon = new Carbon();
 
        $ddr->company_id = $request->input('company_id');
        $ddr->department_id = $request->input('department_id');
        $ddr->reason_of_distribution = $request->input('reason');
        $ddr->date_needed = $carbon::parse($request->input('date_needed'))->toDateTimeString();
        $ddr->document_title = $request->input('document_title');
        $ddr->control_code = $request->input('control_code');
        $ddr->rev_number= $request->input('rev_number');
        $ddr->copy_holder = $request->input('copy_holder');
        $ddr->copy_number = $request->input('copy_number');
        $ddr->date_requested = $carbon::now();
        $ddr->requester_id  = Auth::user()->id;
        $ddr->approver_id = $request->input('approver_id');
        $ddr->status = StatusType::SUBMITTED;

        // $user = User::findOrFail($request->input('approver_id'));
        // $user->notify(new RequesterSubmitDdr($ddr));

        if($ddr->save()){
            return ['redirect' => route('ddr')];
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
            $q->where('role_id', 2);  // approver
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
        $drdrs = Ddr::with(['requester', 'approver', 'company'])->get();

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
        $pdf = PDF::loadView('admin.admin-ddr-pdf');

        return $pdf->stream('ddr.pdf');
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
