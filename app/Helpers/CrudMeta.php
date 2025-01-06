<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

use App\Models\CrudMeta as CrudMetaModel;

use Auth;

class CrudMeta {
    public static function created($data)
    {
        $user = Auth::user();
        $table_name = $data->getTable();

        $meta = CrudMetaModel::create([
            'table' => $table_name,
            'item_id' => $data->id,
            'summery' => $table_name." item created",
            'user_id' => $user->id,
            'ip_address' => $user->ip_address,
            'platform' => $user->platform,
            'device' => $user->device,
            'browser' => $user->browser,
        ]);
    }

    public static function updating($data)
    {
        $user = Auth::user();
        $table_name = $data->getTable();
        $updatedAttributes = $data->getDirty();

        // dd($updatedAttributes);

        if (empty($updatedAttributes) || empty($user)) { return false; }

        $summary = "Updated attributes: " . implode(', ', array_keys($updatedAttributes));
        $meta = CrudMetaModel::create([
            'table' => $table_name,
            'item_id' => $data->id,
            'summery' => $summary,
            'user_id' => $user->id,
            'ip_address' => $user->ip_address,
            'platform' => $user->platform,
            'device' => $user->device,
            'browser' => $user->browser,
        ]);
    }
}