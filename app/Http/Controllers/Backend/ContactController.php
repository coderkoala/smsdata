<?php

namespace App\Http\Controllers\Backend;

use App\Models\models\Contacts as contact;
use App\Models\models\ContactCategory as category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ContactController.
 */
class ContactController
{

    private function validationRules(){
        return [
            "CategoryID" => "bail|required|max:255",
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
                'admin.edit-contact', $id
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
