<?php

namespace App\Models\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\models\ContactCategory as category;
use Exception;

class Contacts extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table='contacts';

    protected $fillable = [
        'CategoryID',
        'ContactID',
        'CategoryName',
        'ContactName',
        'ContactMobile',
        'ContactEmail',
        'IsLive',
    ];

    protected $appends = ['category'];

    protected $primaryKey = 'ContactID';

    public function getCategoryAttribute(){
        try {
          $fetchedData = category::where('CategoryID',$this->CategoryID)->first();
          if ( $fetchedData ) {
              return $fetchedData->toArray();
        } else {
            return null;
          }
        } catch(\Exception $e) {
            dd($e);
            return null;
        }
    }

    public $timestamps = false;
}
