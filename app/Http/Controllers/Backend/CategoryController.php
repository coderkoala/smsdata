<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\models\ContactCategory as category;


/**
 * Class CategoryController.
 */
class CategoryController
{
    private function validationRules()
    {
        return [
            "CategoryName" => "bail|required|max:25",
        ];
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.categories');
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
        $CategoryID = str_pad((string) ((int) DB::selectOne('select max(CategoryID) as max from category')->max + 1), 2, "0", STR_PAD_LEFT);

        // Commit begins here.
        $dataToCommit = $request->only(['CategoryName']);
        $dataToCommit['CategoryID'] = $CategoryID;
        $categoryToCreate = new category();

        // Try or bail.
        try {
            $categoryToCreate->create($dataToCommit);
        } catch (\Exception $ex) {
            return redirect()->route(
                'admin.category'
            )->withFlashError(__('Record creation failed. Reason: ' . $ex->getMessage()));
        }

        return redirect()->route(
            'admin.category'
        )->withFlashSuccess(__('Successfully created contact category: ' . $dataToCommit['CategoryName']));
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function delete($id)
    {
        $categoryToEdit = new category();
        $categoryToEdit = $categoryToEdit::where('CategoryID', $id)->firstOrFail();

        try {
            $categoryToEdit->delete();
        } catch (\Exception $ex) {
            return redirect()->route(
                'admin.category'
            )->withFlashError(__('Couldn\'t delete the requested category'));
        }
        return redirect()->route(
            'admin.category'
        )->withFlashSuccess(__('Successfully deleted the category.'));
    }
}
