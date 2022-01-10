<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Tools;
use App\Http\Controllers\AdminController as BaseAdminController;
use Illuminate\Http\Request;

class AdminController extends BaseAdminController
{
    public function reviewPublishedQueue()
    {
        return parent::reviewPublishedQueue();
    }


    public function reviewPublish($id)
    {
        return parent::reviewPublish($id);
    }


    public function confirmPublish($id)
    {
        $res = parent::confirmPublish($id);
        Tools::notificaUIFlash($res->original["tipo"], $res->original["mensaje"]);

        return redirect()->route('admin.review-publish-queue');
    }


    public function denyPublish($id)
    {
        $res = parent::denyPublish($id);
        Tools::notificaUIFlash($res->original["tipo"], $res->original["mensaje"]);

        return redirect()->route('admin.review-publish-queue');
    }
}
