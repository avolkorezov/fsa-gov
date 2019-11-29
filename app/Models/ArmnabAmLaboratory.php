<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ArmnabAmLaboratory extends Model
{
    protected $table = 'armnab_am_laboratory';

    public $timestamps = true;

    protected $fillable = [
        'AP_NUMBER',
        'STATUS',
        'HGM_NAME',
        'Addresses',
        'PHONE',
        'FAX',
        'EMAIL',
        'HGM_LEADER_NAME',
        'HGM_LEADER_LASTNAME',
        'HGM_LEADER_FATHERNAME',
        'HGMSCOPE_DETAILS',
        'MMATGAA',
        'SCOPE_EXTEND_DATE',
        'SCOPE_EXTEND_CHANGES',
        'SCOPE_EXTEND_MMATGAA',
        'SCOPE_REDUCTION_DATE',
        'SCOPE_REDUCTION_CHANGES',
        'SCOPE_REDUCTION_MMATGAA',
        'AC_NUMBER',
        'AC_BLANKNUMBER',
        'AC_DECISIONNUMBER',
        'AC_DECISIONDATE',
        'AC_STARTDATE',
        'AC_EXPIRATIONDATE',
        'SCOPE_SUSPENSION_DATE',
        'SCOPE_SUSPENSION_CHANGES',
        'SCOPE_STOPAGE_DATE',
        'SCOPE_STOPAGE_CHANGES',
        'AC_CHANGES',
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'AP_NUMBER' => 'required',
        ];
    }
}