<?php 
//date_default_timezone_set('Asia/Kolkata');


function friendstatus($friend_id)
{
  $CI = get_instance();
  $CI->load->model('Chat_model');
  $user_last_activity = $CI->Chat_model->fetch_user_last_activity($friend_id);
   
$date1 = time();
$date2 ='';
if(!empty($user_last_activity)){
$date2 = $user_last_activity[0]['last_activity'];
}
$mins = ($date1 - $date2) / 60;

  if($mins < 10){
        return "f-online";
    }elseif($mins < 15){
        return "f-away";
    } else{
        return "f-off";
    }

}



function newnotification($usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
   $notification = $CI->Chat_model->get_count_notification($usr_id);
    if($notification >= 1){
		 if($notification > 9){
    echo '<b>9+</b>';
		 }else{
			 echo '<b>'.$notification.'</b>'; 
		 }
    }else{

		}
}

function newnotificationapi($usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
   $notification = $CI->Chat_model->get_count_notification($usr_id);
    return $notification;
}


function notification($usr_id)
{
  $CI = get_instance();
  $CI->load->model('Chat_model');
  

  $notification = $CI->Chat_model->get_new_notification($usr_id);
   
		if(!empty($notification)) {
			foreach($notification as $city) {
			    
			    		if($city['image']!=''){$image='<img src="'.base_url().'images/user/small/'.$city['image'].'">';}
		else{$image='<img src="'.base_url().'assets/front/images/author/user.png">';}
		
		
      $rdststus=$city['readstatus'];
	if($rdststus==0){$cls='onclick';}else{$cls='withoutclick';}
	 $newnoti=$city['newnoti'];
	if($newnoti==1){$newold='<span class="tag green">New</span>';}else{$newold='';}
	
	if($city['type']=='Like Video'){
		echo ' 
		<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().'video/'.$city['videoid'].'">
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span>likes Your Video</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	}
	if($city['type']=='Like Image'){
		echo ' 
		<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().'timeline_photos/">
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span>likes Your Photo</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	}
	if($city['type']=='Like Post'){
		echo ' 
		<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().'timeline/'.$city['postid'].'">
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span>liked your post</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	}
	if($city['type']=='Follow'){
		echo ' 
		<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().$city['userid'].'">
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span>started following you.</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
							'.$newold.'
							</li>';
	}			    
	if($city['type']=='Friend Request'){
	    	echo '<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().'timeline_friends" >
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span> want to become friend.</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	}			    
	if($city['type']=='Friend Request Accepted'){
	    
	    echo '<li data="'.$city['id'].'" class="'.$cls.'">
				<a href="'.base_url().$city['userid'].'" >
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span> has accepted your friend request.</span>
										<i>'.timeago($city['date']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	}
			}
			}
		else{
		echo ' <li class="text-center"><img src="'.base_url().'images/noti.gif"><br>
		<span>You do not have any new notification.</span></li> ';
		}
		
		//$CI->Chat_model->update_all_newnoti_notification($usr_id);
 

}


function notificationapi($usr_id)
{
  $CI = get_instance();
  $CI->load->model('Chat_model');
  $CI->Chat_model->update_all_newnoti_notification($usr_id);

  $notification = $CI->Chat_model->get_new_notification($usr_id);
   return  $notification;
}


function newunreadmassage($friendid,$usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
  $notification = $CI->Chat_model->get_unresd_usermsg($friendid,$usr_id);
     if(!empty($notification)){
         $data=  '<b class="unread_new_msg">'.$notification.'</b>'; 
         return $data;
     }else{  }
}

function newunreadmassageapi($friendid,$usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
  $notification = $CI->Chat_model->get_unresd_usermsg($friendid,$usr_id);
      return $notification;
}

function newmsgnotification($usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
  $notification = $CI->Chat_model->get_new_usermsg($usr_id);
     if(!empty($notification)){
          echo'<b>'.count($notification).'</b>'; 
     }else{  }
}

function newmsgnotificationapi($usr_id){
    $CI = get_instance();
  $CI->load->model('Chat_model');
  $notification = $CI->Chat_model->get_new_usermsg($usr_id);
     return count($notification);
}

function newmessages($usr_id)
{
  $CI = get_instance();
  $CI->load->model('Chat_model');
  

  $notification = $CI->Chat_model->get_new_usermsg($usr_id);
   
		if(!empty($notification)) {
			foreach($notification as $city) {
			    $cls = '';
		if($city['image']!=''){$image='<img src="'.base_url().'images/user/small/'.$city['image'].'">';}
		else{$image='<img src="'.base_url().'assets/front/images/author/user.png">';}
		
	$newold='<span class="tag green">New</span>';
		echo ' 
		<li  data-id="'.$city['from_user_id'].'" class="'.$cls.' chat-user">
				<a href="javascript:void(0)">
									'.$image.'
									<div class="mesg-meta">
										<h6>'.$city['d_name'].'</h6>
										<span>has sent you a message</span>
										<span>'.$city['chat_message'].'</span>
										<i>'.timeago($city['timestamp']).'</i>
									</div>
								</a>
								'.$newold.'
							</li>';
	
			}
			}
		else{
		echo '<li class="text-center"><img src="'.base_url().'images/noti.gif"><br>
		<span>You do not have any new messages.</span></li> ';
		}
		
		//$CI->Chat_model->update_all_newnoti_notification($usr_id);
 

}

function newmessagesapi($usr_id)
{
  $CI = get_instance();
  $CI->load->model('Chat_model');
  

  $notification = $CI->Chat_model->get_new_usermsg($usr_id);
   return $notification;

}


?>