<?php

namespace Drupal\navigus\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;


class NavigusViewersRefreshController {

 /**
   * @return JsonResponse
   */
  public function content() {
    $current_user_uid = \Drupal::currentUser()->id();

    $node = \Drupal::entityTypeManager()->getStorage('node')->load(1);

    $users = [];
	$current_visitors = $node->get('field_current_viewers')->getValue();
	foreach($current_visitors as $key => $item) {
	  $uid = $item['value'];
      $users[$uid] = $uid; 
	}

    if(isset($users[0])) unset($users[0]);

	// Get last access timestamp of all users
	/*$query = db_query('SELECT entity_id as uid, field_last_access_value as last_access FROM user__field_last_access');
	$results = $query->fetchAll();
	foreach($results as $result) {
		if(!empty($result->last_access)){
          $seconds_diff = time() - $result->last_access;
		 // print_r($seconds_diff); die;
		  if($seconds_diff > 120 && isset($result->uid)){
          //$time = ($seconds_diff/3600);
		    unset($users[$result->uid]);
		  }
		}
	}*/
	//print_r($results); die;


	if(!isset($users[$current_user_uid])) {
      $users[$current_user_uid] = $current_user_uid;
	  $node->set('field_current_viewers', array_keys($users));
	  $node->save();

	  // Update user last access value
	  $user = \Drupal::entityTypeManager()->getStorage('user')->load($current_user_uid);
	  $user->set('field_last_access', time());
	  $user->save();
	}

	if(!empty($users)) {
		$output = '<div class="current_viewer">';
		$output .= '<ul style="list-style:none;margin:0px;padding:0px;overflow:hidden;">';
       $users = \Drupal::entityTypeManager()->getStorage('user')->loadMultiple($users);
	   foreach($users as $user) {
         $username = $user->getUserName();
		 $firstCharacter = substr($username, 0, 1);
		  if (!$user->user_picture->isEmpty()) {
            $userPicture = $user->user_picture->view('large');
			$output .= '<li style="width:50px; height:50px;border-radius:180px;float:left;" title="'.$username.'">' .render($userPicture) .'</li>';
         }else {
            $output .= '<li style="width:50px; height:50px;border-radius:180px;float:left;" title="'.$username.'">' .strtoupper($firstCharacter).'</li>';
		 }

	   }
	   $output .= '</ul>';
	   $output .= '</div>';
	}

    return new JsonResponse([ 'data' => $output, 'method' => 'GET', 'status'=> 200]);
  }
}

