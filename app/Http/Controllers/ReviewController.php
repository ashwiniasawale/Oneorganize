<?php

namespace App\Http\Controllers;

use App\Models\ProjectStage;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskFile;
use App\Models\TaskStage;
use App\Models\TimeTracker;
use App\Models\User;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Review;
use App\Models\ProjectUser;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //
    public function review($project_id)
    {

        $user = Auth::user();
         if($user->can('view project review'))
         {
             $project = Project::find($project_id);

            if(!empty($project) && $project->created_by == Auth::user()->creatorId())
            {

                if($user->type != 'company')
                {
                    if(\Auth::user()->type == 'client'){
                      $review = Review::where('project_id',$project->id)->get();
                    }else{
                        $review = Review::where('project_id',$project->id)->get();
                     // $review = Review::where('project_id',$project->id)->whereRaw("find_in_set('" . $user->id . "',attended_by)")->get();
                    }
                }

               
                if($user->type == 'company' || $user->type == 'Manager')
                {
                    $review = Review::where('project_id', '=', $project_id)->get();
                }
                

                return view('project_review.review', compact('project','review'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    
    public function reviewCreate($project_id)
    {
        if(\Auth::user()->can('create project review'))
        {

            $project_user = ProjectUser::where('project_id', $project_id)->get();
            
            $users        = [];
            foreach($project_user as $key=>$user)
            {

                $user_data = User::find($user->user_id);
                $key = $user->user_id;
                $user_name = !empty($user_data)? $user_data->name:'';
                $users[$key] = $user_name;
            }

            return view('project_review.reviewCreate', compact( 'project_id', 'users'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function reviewStore(Request $request, $project_id)
    {
        if(\Auth::user()->can('create project review'))
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'review_date' => 'required',
                                   'attended_by' => 'required',
                                   'risk_identified' => 'required',
                                  
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.review.index', $project_id)->with('error', $messages->first());
            }

           
            $usr         = \Auth::user();
          
            $review              = new Review();
         
            $review->project_id  = $project_id;
            $review->review_date       = $request->review_date;
            $review->attended_by    = $request->attended_by;
            $review->artifacts_of_review  = $request->artifacts_of_review;
            if($request->hasFile('checklist'))
            {
              
                //storage limit
                $file_size = $request->file('checklist')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);
                if($result==1)
                {
                    $fileName = time() . '.' . $request->checklist->extension();
                    $request->file('checklist')->storeAs('project_review', $fileName);
                    $review->checklist      = 'project_review/'.$fileName;
                }
            }
            
           // $review->review_criteria = $request->review_criteria;
            $review->requirement      = $request->requirement;
            $review->non_conf_list   = $request->non_conf_list;
            $review->improvement_suggestions   = $request->improvement_suggestions;
            $review->risk_identified   = $request->risk_identified;
            $review->problem_discover   = $request->problem_discover;
            $review->deviation_taken   = $request->deviation_taken;
            $review->is_updated   = $request->is_updated;
            $review->created_by  = \Auth::user()->id;
            $review->save();

        
            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'log_type' => 'Create Review',
                    'remark' => json_encode(['title' => $review->review_date]),
                ]
            );

           

            return redirect()->route('project.review.index', $project_id)->with('success', __('Review successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function reviewEdit($project_id, $review_id)
    {
        if(\Auth::user()->can('edit project review'))
        {
            $review          = Review::find($review_id);
           // $priority     = Bug::$priority;
          //  $status       = BugStatus::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('title', 'id');
            $project_user = ProjectUser::where('project_id', $project_id)->get();
            $users        = array();
            foreach($project_user as $user)
            {
              $user_data = User::where('id',$user->user_id)->first();
              $key = $user->user_id;
              $user_name = !empty($user_data) ? $user_data->name:'';
              $users[$key] = $user_name;
            }
         
            return view('project_review.reviewEdit', compact('project_id', 'review','users'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

    public function reviewUpdate(Request $request, $project_id, $review_id)
    {


        if(\Auth::user()->can('edit project review'))
        {
            $validator = \Validator::make(
                $request->all(), [

                    'review_date' => 'required',
                    'attended_by' => 'required',
                    'risk_identified' => 'required',
                   
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.review.index', $project_id)->with('error', $messages->first());
            }
            $review              = Review::find($review_id);
            $review->project_id  = $project_id;
            $review->review_date       = $request->review_date;
            $review->attended_by    = $request->attended_by;
            $review->artifacts_of_review  = $request->artifacts_of_review;
            if($request->hasFile('checklist'))
            {
              
                $file_path = $review->checklist;
                $file_size = $request->file('checklist')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);

                if($result==1) {
                    Utility::checkFileExistsnDelete([$review->checklist]);
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $fileName = time() . '.' . $request->checklist->extension();
                    $request->file('checklist')->storeAs('project_review', $fileName);
                    $review->checklist = 'project_review/' . $fileName;
                }
            }
           
           // $review->review_criteria = $request->review_criteria;
            $review->requirement      = $request->requirement;
            $review->non_conf_list   = $request->non_conf_list;
            $review->improvement_suggestions   = $request->improvement_suggestions;
            $review->risk_identified   = $request->risk_identified;
            $review->problem_discover   = $request->problem_discover;
            $review->deviation_taken   = $request->deviation_taken;
            $review->is_updated   = $request->is_updated;
         
            $review->save();

            return redirect()->route('project.review.index', $project_id)->with('success', __('Review successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function reviewDestroy($project_id, $id)
    {
             $review = Review::find($id);
            if(\Auth::user()->can('delete project review'))
            {
                Review::where('project_id', '=', $review->project_id)->where('id', '=', $id)->delete();
                $path        = storage_path($review->checklist);
                if(file_exists($path))
                {
                    \File::delete($path);
                }
                return redirect()->back()->with('success', __('Project Review Record successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }

    }

}
