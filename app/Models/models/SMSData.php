<?php

namespace App\Models\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\models\ContactCategory as category;
use Exception;

class SMSData extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table='smsdata';

    protected $fillable = [
        'ID',
        'PhoneNo',
        'SmsText',
        'isSent',
        'SentDate',
        'CreatedBy',
    ];

    protected $appends = ['user'];

    protected $primaryKey = 'ID';

    public function getUserAttribute(){
        return $this->CreatedBy;
        // try {
        //   $fetchedData = category::where('CategoryID',$this->CategoryID)->first();
        //   if ( $fetchedData ) {
        //       return $fetchedData->toArray();
        // } else {
        //     return null;
        //   }
        // } catch(\Exception $e) {
        //     dd($e);
        //     return null;
        // }
    }

    public $timestamps = false;
}
