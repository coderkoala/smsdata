<?php

namespace App\Http\Controllers\Backend;

use App\Models\models\Contacts as contact;
use App\Models\models\ContactCategory as category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Class ContactController.
 */
class ContactController
{

    private function validationRules()
    {
        return [
            "CategoryID" => "bail|required|max:255",
        ];
    }

    private function validationRulesBulk()
    {
        return [
            "CategoryID" => "bail|required|max:255",
            "contactFile" => "bail|required|file|mimes:xlsx,xls",
        ];
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.contacts');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function add()
    {
        try {
            $newID = str_pad((string) ((int) DB::selectOne('select max(ContactID) as max from contacts')->max + 1), 5, "0", STR_PAD_LEFT);
        } catch (\Exception $ex) {
            $newID = '00001';
        }
        return view(
            'backend.contacts_add',
            array(
                'contact' => [
                    'ContactID' => $newID
                ],
                'categories' => category::all()->toArray(),
            )
        );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function bulkAdd()
    {
        return view(
            'backend.contacts_bulk',
            array(
                'categories' => category::all()->toArray(),
            )
        );
    }

    /**
     * @param Integer $iterator
     * @return Integer
     */
    private function getPaddedIterator($iterator)
    {
        return str_pad(((string)$iterator), 5, "0", STR_PAD_LEFT);
    }

    /**
     * @param Request $request
     * @return Boolean
     */
    private function storeContacts($contact)
    {
        DB::beginTransaction();
        $regex = '/98[0-9]{8}/i';
        $iterator = null;
        $iterator = ((int) DB::selectOne('select max(ContactID) as max from contacts')->max + 1);
        $contactID = $this->getPaddedIterator($iterator);

        $data = new stdClass();
        $data->ContactID = $contactID;
        $data->CategoryID = $this->tempCategory;
        $data->isLive = 'Y';

        for ($i = 1; $i < count($contact); $i++) {
            $matches = [];
            $data->ContactName = $contact[$i][0];
            $data->ContactEmail = $contact[$i][2];
            $data->ContactID = $contactID;
            $phone = isset($contact[$i][1]) ? $contact[$i][1] : null;
            if ( strlen($phone) < 10 ) {
                DB::rollBack();
                $this->success = false;
                return redirect()->back()->withErrors('The excel file contains invalid Mobile Numbers. Mobile numbers should at least be of length 10')->withInput();
            } else {
                if ( preg_match($regex, $phone, $matches) && isset($matches[0])) {
                    $data->ContactMobile = (string) floor($matches[0]);
                } else {
                    DB::rollBack();
                    $this->success = false;
                    return redirect()->back()->withErrors('One or more mobile numbers are invalid. Please make sure numbers begin with \'98\'')->withInput();
                }

                // Save the contact.
                try {
                    // Make a new contact.
                    $newContact = new contact((array)$data);
                    $newContact->save();
                    ++$iterator;
                    $contactID = $this->getPaddedIterator($iterator);
                } catch(\Exception $e){
                    DB::rollBack();
                    $this->success = false;
                    if( $e->getCode() === '23000') {
                        return redirect()->back()->withErrors('The system detected duplicate records. All changes have been rolled back.')->withInput();
                    }
                    return redirect()->back()->withErrors('The excel file couldn\'t be parsed. Please reinspect the file, and try again.')->withInput();
                }
            }
        }
        DB::commit();
        return $this->success = true;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeBulk(Request $request)
    {
        // Validate the request...
        $validator = \Validator::make(request()->all(), $this->validationRulesBulk());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // Assign Category ID locally in the object.
        $this->tempCategory = $request->CategoryID;
        $this->success = false;

        // Store $request->contactFile in storage...
        $filePtr = $request->contactFile;
        $filePtrRealExtension = $filePtr->getClientOriginalExtension();
        // Get file extension from $filePtr

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
        if ($filePtr) {
            $spreadsheet = $reader->load($filePtr);
            $allSheets = $spreadsheet->getAllSheets();

            // temp code
            $allSheets = array_reverse($allSheets);
            //temp code
            foreach ($allSheets as $sheet) {
                // Reverse array $sheet.
                $sheetData = $sheet->toArray();
                if ($sheetData !== [[0 => null]]) {
                    $this->storeContacts($sheetData);
                }
            }
        } else {
            return redirect()->back()->withErrors('The excel file couldn\'t be parsed.')->withInput();
        }

        if ( $this->success === true ) {
            return redirect(route('admin.contact'))->withFlashSuccess('The contacts have been sucessfully imported.');
        } else {
            return redirect()->back()->withInput();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function post(Request $request)
    {
        // Validate shit.
        $validationRules = $this->validationRules();
        $request->validate($validationRules);

        // Find max and pad it to 2 digits.
        $ContactID = str_pad((string) ((int) DB::selectOne('select max(ContactID) as max from contacts')->max + 1), 5, "0", STR_PAD_LEFT);

        // Commit begins here.
        $dataToCommit = $request->only(['CategoryID', 'ContactName', 'ContactMobile', 'ContactEmail', 'IsLive']);
        $dataToCommit['ContactID'] = $ContactID;
        $contactToCreate = new contact();

        // Try or bail.
        try {
            $contactToCreate->create($dataToCommit);
        } catch (\Exception $ex) {
            return redirect()->route(
                'admin.add-contact'
            )->withFlashError(__('Record creation failed. Reason: ' . $ex->getMessage()));
        }

        return redirect()->route(
            'admin.view-contact',
            $ContactID
        )->withFlashSuccess(__('Successfully created new contact record for: ' . $dataToCommit['ContactName']));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patch($id, Request $request)
    {
        $contactToEdit = new contact();
        $contactToEdit = $contactToEdit::where('ContactID', $id)->firstOrFail();

        // Validate shit.
        $validationRules = $this->validationRules();
        $request->validate($validationRules);

        // Commit begins here.
        $dataToCommit = $request->only(['CategoryID', 'ContactName', 'ContactMobile', 'ContactEmail', 'IsLive']);
        $dataToCommit['ContactID'] = $id;

        // Try or bail.
        try {
            $contactToEdit->update($dataToCommit);
        } catch (\Exception $ex) {
            return redirect()->route(
                'admin.edit-contact',
                $id
            )->withFlashError(__('Record update failed. Reason: ' . $ex->getMessage()));
        }

        return redirect()->route(
            'admin.view-contact',
            $id
        )->withFlashSuccess(__('Successfully updated the contact record for: ' . $dataToCommit['ContactName']));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id, $template = 'backend.contacts_edit')
    {
        $id = str_pad((string)$id, 5, "0", STR_PAD_LEFT);
        $contact = contact::where('ContactID', $id)->first();
        if ($contact) {
            $contact = $contact->toArray();
        }

        // Get Contact Information for :id.
        return view(
            $template,
            array(
                'contact' => $contact,
                'categories' => category::all()->toArray(),
            )
        );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view($id)
    {
        return $this->edit($id, 'backend.contacts_view');
    }
}
