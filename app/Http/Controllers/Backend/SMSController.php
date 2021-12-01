<?php

namespace App\Http\Controllers\Backend;

use App\Models\models\SMSData as smsData;
use App\Models\models\Contacts as contacts;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function bulk()
    {
        return view('backend.sendSMSBulk');
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

    // Function to take sheet data and iterate through it to save to database.
    private function createSMS($sheetData) {
        DB::beginTransaction();
        $regex = '/98[0-9]{8}/i';
        $matches = null;
        $data = (object)[];
        $data->CreatedBy = auth()->user()->name;

        // Check if $data->CreatedBy is longer than 10 characters, truncate it to 10 characters.
        if (strlen($data->CreatedBy) > 10) {
            $data->CreatedBy = substr($data->CreatedBy, 0, 10);
        }

        for($i=1; $i < count($sheetData); $i++) {
            $data->PhoneNo = $sheetData[$i][0];
            $data->SmsText = $sheetData[$i][1];

            // Assign phone safely.
            $phone = isset($sheetData[$i][0]) ? $sheetData[$i][0] : null;
            if ( preg_match($regex, $phone, $matches) && isset($matches[0])) {
                $data->PhoneNo = (string) floor($matches[0]);
            } else {
                DB::rollBack();
                $this->success = false;
                return redirect()->back()->withErrors('One or more mobile numbers are invalid. Please make sure numbers begin with \'98\'')->withInput();
            }

            // Check if $data->SmsText is less than or equal to 160 characters. Truncate if not.
            if (strlen($data->SmsText) > 160) {
                $data->SmsText = substr($data->SmsText, 0, 160);
            }

            // Create new SMSData object.
            try {
                $smsData = new smsData( (array) $data );
                $smsData->save();
            } catch(\Exception $e) {
                DB::rollBack();
                $this->success = false;
                return redirect()->back()->withErrors('An error occurred while saving your SMS request.')->withInput();
            }
        }

        $this->success = true;
        DB::commit();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function postBulk(Request $request)
    {
        $this->success = false;
        $validation = Validator::make( $request->all(), [
            'smsFile' => 'required|file|mimes:xlsx,xls',
        ]);

        if( $validation->fails() ) {
            return redirect()->back()->withErrors( $validation->errors() )->withInput();
        }

        // Store $request->contactFile in storage...
        $filePtr = $request->smsFile;
        $filePtrRealExtension = $filePtr->getClientOriginalExtension();
        $reader = null;

        switch ($filePtrRealExtension) {
            case 'xlsx':
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
                $reader->setReadDataOnly(true);
                break;

            case 'xls':
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
                $reader->setReadDataOnly(true);
                break;
        }

        if ($filePtr && $reader) {
            $spreadsheet = $reader->load($filePtr);
            $allSheets = $spreadsheet->getAllSheets();

            foreach ($allSheets as $sheet) {
                // Reverse array $sheet.
                $sheetData = $sheet->toArray();
                if ($sheetData !== [[0 => null]]) {
                    $this->createSMS($sheetData);
                }
            }
        } else {
            return redirect()->back()->withErrors('The excel file couldn\'t be parsed.')->withInput();
        }

        if ( $this->success === true ) {
            return redirect(route('admin.dashboard'))->withFlashSuccess('The contacts have been sucessfully imported.');
        } else {
            return redirect()->back()->withInput();
        }
    }
}
