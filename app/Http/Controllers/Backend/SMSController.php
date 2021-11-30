<?php

namespace App\Http\Controllers\Backend;

use App\Models\models\SMSData as smsData;
use App\Models\models\Contacts as contacts;
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
    private function bulkDispatch(Request $request)
    {
        // Check if $request->GroupToText is empty array.
        if (empty($request->GroupToText)) {
            return false;
        }

        // Get the currently logged in user's id.
        $userId = auth()->user()->name;


        // Store request attributes to their respective mapped variables.
        $data = (object)[];
        $data->SmsText = $request->SmsText;

        //If $data->CreatedBy is longer than 10 characters, truncate it to 10 characters.
        if (strlen($userId) > 10) {
            $data->CreatedBy = substr($userId, 0, 10);
        } else {
            $data->CreatedBy = $userId;
        }

        // Loop through each individual number and create a new SMSData object.
        foreach ($request->GroupToText as $number) {
            $fetchedContacts = contacts::where('categoryid', $number)->get();
            $fetchedContacts = $fetchedContacts->toArray();

            // Iterate through each fetched contacts and insert them into database.
            foreach ($fetchedContacts as $contact) {
                $data->PhoneNo = $contact['ContactMobile'];
                $smsData = new smsData( (array) $data );
                $smsData->save();
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @return boolean
     */
    private function singleDispatch(Request $request)
    {

        // Check if $request->IndividualToText is empty array.
        if (empty($request->IndividualToText)) {
            return false;
        }

        // Get the currently logged in user's id.
        $userId = auth()->user()->name;


        // Store request attributes to their respective mapped variables.
        $data = (object)[];
        $data->SmsText = $request->SmsText;

        //If $data->CreatedBy is longer than 10 characters, truncate it to 10 characters.
        if (strlen($userId) > 10) {
            $data->CreatedBy = substr($userId, 0, 10);
        } else {
            $data->CreatedBy = $userId;
        }

        // Loop through each individual number and create a new SMSData object.
        foreach ($request->IndividualToText as $number) {
            $number = str_pad((string) $number, 5, '0', STR_PAD_LEFT);
            $contactCategory = contacts::where('contactid', $number)->first();
            if ( $contactCategory ) {
                $number = $contactCategory->ContactMobile;
                $data->PhoneNo = $number;
                $smsData = new smsData( (array) $data );
                $smsData->save();
            }
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function post(Request $request)
    {
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
