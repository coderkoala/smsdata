<?php

namespace App\Http\Controllers\Backend;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        return view('backend.sendSMS');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function add()
    {
        return view('backend.sendSMS');
    }


    /**
     * @param Request $request
     * @return boolean
     */
    private function singleDispatch(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return boolean
     */
    private function bulkDispatch(Request $request)
    {

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function post(Request $request)
    {
        // Validation of the form.
        $validation = Validator::make( $request->all(), [
            'SmsText'=>'required|max:160',
            'dispatchType' => [
                'required',
                Rule::in(['single', 'bulk']),
            ],
            'IndividualToText' => [
                'nullable',
                'array'
            ],
            'GroupToText' => [
                'nullable',
                'array'
            ],
        ]);

        if( $validation->fails() ) {
            return redirect()->back()->withErrors( $validation->errors() )->withInput();
        }

        if( empty($request->IndividualToText) && empty( $request->GroupToText ) ) {
            return redirect()->back()->withErrors( 'You must select at least one recipient.' )->withInput();
        }

        switch($request->dispatchType){
            case 'single':
                // Single Dispatch.
                $this->singleDispatch($request);
                break;
            case 'bulk':
                // Bulk Dispatch.
                $this->bulkDispatch($request);
                break;
        }

        return redirect()->back()->withFlashSuccess( 'Successfully dispatched your sms request.' );
    }
}
