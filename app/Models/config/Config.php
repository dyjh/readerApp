<?php

namespace App\Models\config;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Config extends Model
{
    protected $table = 'configs';

    protected $fillable = [
        'module',
        'name',
        'value',
        'type'
    ];

    public $timestamps = false;

    public static function get(string $module, string $key, $default = null)
    {
        // Redis::set($key, $code);
        // Redis::expire($key, 300);
        $setting = self::where([
            'module' => $module,
            'name' => $key
        ])->first();

        if ($setting) {
            if ($setting->type == 'json') {
                return json_decode($setting->value, true);
            }
            return $setting->value;
        }
        return $default;
    }

    public static function batchSet(string $module, array $configs)
    {
        DB::transaction(function () use ($configs, $module) {
            foreach ($configs as $key => $value) {
                $type = null;
                if (is_array($value)) {
                    $value = json_encode($value);
                    $type = 'json';
                }

                $config = self::where([
                    'module' => $module,
                    'name' => $key,
                ])->first();

                if ($config) {
                    $config->value = $value;
                    $config->type = $type;
                    $config->save();
                } else {
                    self::create([
                        'module' => $module,
                        'name' => $key,
                        'value' => $value,
                        'type' => $type
                    ]);
                }
            }
        });
    }
}
