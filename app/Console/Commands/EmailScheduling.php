<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Drdr;
use App\User;
use App\Types\StatusType;
use Carbon\Carbon;
use App\Notifications\SchedulingNotifyApproverDrdr;
use App\Notifications\SchedulingNotifyReviewerDrdr;

class EmailScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        
        $drdrSubmitted = Drdr::where('status', StatusType::SUBMITTED)->whereDate('effective_date','=', Carbon::today()->subDays(-1)->toDateTimeString())->get();
        $drdrReviewed = Drdr::where('status', StatusType::APPROVED_REVIEWER)->whereDate('effective_date','=', Carbon::today()->subDays(-1)->toDateTimeString())->get();
        if($drdrSubmitted){
            $reviewerId = $drdrSubmitted->pluck('reviewer_id');
            foreach($reviewerId as $key => $id){
                $reviewer = User::findOrFail($id);
                $reviewer->notify(new SchedulingNotifyReviewerDrdr($drdrSubmitted[$key], $drdrSubmitted[$key]->reviewer_id));
            }
        }

        if($drdrReviewed){
            $approverId = $drdrReviewed->pluck('approver_id');
            foreach($approverId as $key => $id){
                $approver = User::findOrFail($id);
                $approver->notify(new SchedulingNotifyApproverDrdr($drdrReviewed[$key], $drdrReviewed[$key]->approver_id));
            }
        }
        
    }
}
