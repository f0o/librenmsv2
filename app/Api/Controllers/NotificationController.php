<?php

namespace App\Api\Controllers;

use Dingo\Api\Http;
use Dingo\Api\Routing\Helpers;
use App\Notification;
use App\NotificationAttrib;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    use Helpers;

    public function __construct() {
    }

    /**
     * Display a listing of all notifications
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type = null)
    {
        if ($type === 'archive')
        {
            $notifications = Notification::IsArchived($request)->get();
        }
        else {
            $notifications = Notification::IsUnread()->get();
        }
        return $notifications;
    }

    public function update(Request $request, $id, $action)
    {

        $notification = Notification::find($id);
        $enable = strpos($action, 'un') === false;
        if(!$enable) {
            $action = substr($action, 2);
        }

        if ($action == 'read') {
           $result = $notification->markRead($enable);
        }
        elseif ($action == 'sticky') {
           $result = $notification->markSticky(false);
        }

        if ($result === false) {
            return $this->response->errorInternal();
        }
        else {
            return $this->response->array(array('statusText'=>'OK'));
        }
    }

}
