<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Class Source
 *
 * @property string $hash
 * @property string $source
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereSource($value)
 * @mixin \Eloquent
 * @property int $id
 * @property string $donor_class_name
 * @property string $name
 * @property string $image
 * @property string $desc
 * @property bool $parseit
 * @property bool $available
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereAvailable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereDonorClassName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereParseit($value)
 * @property int $version
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereVersion($value)
 * @property string $param
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Source whereParam($value)
 */
class Source extends Model
{
    protected $table = 'sources';

    public $timestamps = true;

    protected $fillable = [
        'donor_class_name',
        'name',
        'image',
        'desc',
        'hash',
        'source',
        'parseit',
        'param',
        'version',
        'available'
    ];

    public static function rules()
    {
        return [
            'donor_class_name' => 'required',
            'hash' => 'required',
            'source' => 'required',
        ];
    }

    protected $guarded = [];

    public function countSourcesByDonor()
    {
        return Source::where(['donor_class_name' => $this->donor_class_name])
            ->where(['available' => 1])->get(['id'])->count();
    }

    public function countSourcesParsingByDonor()
    {
        return Source::where(['donor_class_name' => $this->donor_class_name])
            ->where(['available' => 1])
            ->where(['parseit' => 1])
            ->get(['id'])->count();
    }

    public static function saveOrUpdate($attr = [])
    {
        if ( isset($attr['hash_old']) )
        {
            if ( $model = static::where(['hash' => $attr['hash_old']])->get()->first() )
            {
                $model->update([
                    'name' => $attr['name'],
                    'hash' => $attr['hash'],
                    'image' => empty(@$attr['image']) ? $model->image : $attr['image'],
                    'desc' => empty(@$attr['desc']) ? $model->desc : $attr['desc'],
                    'param' => empty(@$attr['param']) ? serialize('') : serialize(@$attr['param']),
                    'available' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ( $model = static::where(['hash' => $attr['hash']])->get()->first() )
        {
            $model->update([
                'name' => @$attr['name'],
                'hash' => @$attr['hash'],
                'image' => empty(@$attr['image']) ? $model->image : @$attr['image'],
                'desc' => empty(@$attr['desc']) ? $model->desc : @$attr['desc'],
                'param' => empty(@$attr['param']) ? serialize('') : serialize(@$attr['param']),
                'available' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return 'update';
        }
        else
        {
            static::insert([
                'donor_class_name' => $attr['donor_class_name'],
                'name' => $attr['name'],
                'image' => empty(@$attr['image']) ? null : $attr['image'],
                'desc' => empty(@$attr['desc']) ? null : $attr['desc'],
                'param' => empty(@$attr['param']) ? serialize('') : serialize(@$attr['param']),
                'hash' => $attr['hash'],
                'source' => $attr['source'],
                'created_at' => date('Y-m-d H:i:s'),
                'version' => $attr['version']
            ]);
            return 'insert';
        }
    }
}