<?php

namespace Helious\SeatNotifications\Http\Controllers;

use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Eveapi\Models\Corporation\CorporationStructureService;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    /**
     * Show the eligibility checker.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $dataTable->render('seat-beacons::beacons.settings');
    }


}
