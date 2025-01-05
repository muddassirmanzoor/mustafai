<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = [];

    function getFriendConversation($friendId,$lastIds='',$myID='',$limit=1000)
    {
        \DB::statement("SET SQL_MODE=''");

        $myID = ($myID) ? $myID : Auth::user()->id;

        $origialIds = $lastIds;
        $lastIds = explode(',',$lastIds);

        // if($lastIds[0] != 0)
        // {
        //     $where = '(`chats`.`from_id` = '.$myID.' and `chats`.`to_id` = '.$friendId.' and deleted_by is NULL and chats.id NOT IN('.$origialIds.')) OR (`chats`.`to_id` = '.$myID.' and `chats`.`from_id` = '.$friendId.' and deleted_by is NULL and chats.id NOT IN('.$origialIds.'))';
        // }
        // else
        // {
        //     $where = '(`chats`.`from_id` = '.$myID.' and deleted_by is NULL and `chats`.`to_id` = '.$friendId.') OR (`chats`.`to_id` = '.$myID.' and deleted_by is NULL and `chats`.`from_id` = '.$friendId.')';
        // }

        if($lastIds[0] != 0)
        {
            $where = '(`chats`.`from_id` = '.$myID.' and `chats`.`to_id` = '.$friendId.' and ( find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0 ) and chats.id NOT IN('.$origialIds.')) OR (`chats`.`to_id` = '.$myID.' and `chats`.`from_id` = '.$friendId.' and (find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0) and chats.id NOT IN('.$origialIds.'))';
        }
        else
        {
            $where = '(`chats`.`from_id` = '.$myID.' and ( find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0 ) and `chats`.`to_id` = '.$friendId.') OR (`chats`.`to_id` = '.$myID.' and (find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0) and `chats`.`from_id` = '.$friendId.')';
        }

        Chat::where(['from_id'=>$friendId,'to_id'=>$myID])->update(['status'=>1]);

        return Chat::select('chats.id','chats.type','chats.from_id','chats.to_id','chats.message','chats.deleted_by','chats.is_deleted','chats.created_at','chats.status')
        ->whereNotIn('id',$lastIds)
        // ->whereNull('deleted_by')
        ->whereRaw($where)
        ->orderBy('chats.id','ASC')
        ->groupBy('chats.id')
        ->limit($limit)
        ->get();
    }

    function getFriendChat($friendId,$lastMessage=0)
    {
        \DB::statement("SET SQL_MODE=''");

        $myID = auth()->user()->id;

        // $where = '(`chats`.`from_id` = '.$myID.' and ( find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0 ) and `chats`.`to_id` = '.$friendId.') OR (`chats`.`to_id` = '.$myID.' and (find_in_set('.$myID.',deleted_by) is null or find_in_set('.$myID.',deleted_by) = 0) and `chats`.`from_id` = '.$friendId.')';

        $where = '1 AND ( (`chats`.`from_id` = '.$myID.' and `chats`.`to_id` = '.$friendId.') OR (`chats`.`to_id` = '.$myID.' and `chats`.`from_id` = '.$friendId.') )  AND `chats`.`id` > '.$lastMessage.'';

        Chat::where(['from_id'=>$friendId,'to_id'=>$myID])->update(['status'=>1]);

        return Chat::select('chats.id','chats.type','chats.from_id','chats.to_id','chats.message','chats.is_deleted','deleted_by','chats.created_at','chats.status')
        ->whereRaw($where)
        ->orderBy('chats.id','ASC')
        ->groupBy('chats.id')
        ->get();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id','from_id');
    }
}
