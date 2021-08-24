<?php

namespace App\Http\Controllers\Backend;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SMSController.
 */
class SMSController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.sendSMSDashboard');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function add()
    {
        return view('backend.sendSMS');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function post(Request $request)
    {
        dd($request->all());
        return view('backend.sendSMS');
    }
}
