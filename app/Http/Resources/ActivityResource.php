<?php
namespace App\Http\Resources;

use App\Models\Admin\EmployeeSection;
use App\Models\Posts\Post\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
class ActivityResource extends JsonResource
{

    public function toArray($request)
    {
        $postId = basename(parse_url($this->link, PHP_URL_PATH));
        if (!ctype_digit($postId)) {
            $postId = hashDecode($postId);
        }
        return [

            'id' => $this->id,
            'userId' => $this->user_id,
            'body' => $this->body,
            'postType'=> optional(Post::find($postId))->post_type,
            'isPostExist'=> optional(Post::find($postId))->post_type ? true :  false,
            'postId'=>$postId,
            'created_at'=>$this->created_at->diffForHumans(),


        ];
    }

}
