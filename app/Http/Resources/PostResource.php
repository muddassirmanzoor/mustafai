<?php

namespace App\Http\Resources;

use App\Models\User\ApplyJob;
use Illuminate\Http\Resources\Json\JsonResource;
use LanguageDetection\Language;
class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $title_lang='urdu';
        if(!empty($this->{'title_'.$request->lang}) || isset($this->{'title_'.$request->lang})){
            $ld = new Language(['en', 'ur']);
            $textD=$ld->detect($this->{'title_'.$request->lang});
            $title_lang=$textD['en'] === 0.0 ? 'urdu' : 'english';
        }
        // dd(auth()->user());
        $isApplied = ApplyJob::where('job_post_id', $this->id)->where('user_id', $request->login_user_id)->exists();
        $user_image='';
        /** USER IMAGE***/
        if (isset($this->user->profile_image)) {

            $path  = getS3File($this->user->profile_image);
            if (file_exists(public_path($this->user->profile_image))) {
                $user_image =  $path;
            } else {

                $user_image =  getS3File("images/product-placeholder-image.png");
            }
        }

        /** POST IMAGE***/
        $post_image = "";
        if (isset($this->file->file)) {

            $path  = getS3File($this->file->file);

            if (file_exists(public_path($this->file->file))) {
                $post_image =  $path;
            } else {
                $post_image =  getS3File("images/product-placeholder-image.png");
            }
        }


        return [

            'postId' => $this->id,
            // 'title_english' => $this->title_english,
            'title_english' => $this->{'title_'.$request->lang},
            'title_lang' => $title_lang,
            'description_english' => $this->description_english,
            'post_type' => $this->post_type,
            'imageURL' => $user_image,
            'username' => isset($this->user->user_name)?$this->user->user_name:'',
            'postDetail' => $this->description_english,
            'postImage' => $post_image,
            'likeCount' => $this->likes_count,
            'commentCount' => $this->comments_count,
            'resume' => ($this->job_type == 2) && !empty($this->resume) ? getS3File($this->resume) : "",
            'job_seeker_name' => $this->job_seeker_name,
            'job_seeker_currently_working' => (!empty($this->job_seeker_currently_working) && $this->job_seeker_currently_working!="null")? $this->job_seeker_currently_working :null,
            'hiring_company_name' => (!empty($this->hiring_company_name) && $this->hiring_company_name!="null")? $this->hiring_company_name :null,
            'email' => $this->job_seeker_or_hire_email,
            'phone' => $this->job_seeker_or_hire_phone,
            'experience' => $this->experience,
            'city' =>$this->citi,
            'hospital' =>$this->hospital,
            'address' =>$this->address,
            'blood_group' =>$this->blood_group,
            'blood_for' =>$this->blood_for,
            'occupation' =>$this->occupation,
            'job_type' =>$this->job_type,
            'skills' => $this->skills,
            'timeOfPost' => (string) $this->created_at,
            'comments' => CommentResource::collection($this->comments),
            'userDetail'=>new UserInfoResource($this->user),
            'admin'=> new AdminInfoResource($this->admin),
            'shareUser'=>new UserInfoResource($this->shareUser),
            'post_images'=>$this->images,
            'status'=>!empty($request->userId) ? ($this->status==1) : '',
            'isApplied'=>!empty($request->login_user_id) ? $isApplied :'',
            'is_like' => !empty($request->userId) ? $this->is_like : ''


        ];
    }
}
