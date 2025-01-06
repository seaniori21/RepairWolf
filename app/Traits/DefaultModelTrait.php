<?php
    // DefaultModelTrait.php

    namespace App\Traits;

    use App\Helpers\CrudMeta;
    use App\Models\CrudMeta as CrudMetaModel;
    
    trait DefaultModelTrait
    {
        public function crudMeta() {
            $response = collect();

            $item_id = $this->id;
            $table_name = $this->getTable();

            $data = CrudMetaModel::where([['table', $table_name],['item_id',$item_id]])->orderBy('created_at', 'desc')->get();

            if ($data) { $response = $data; }

            return $response;
        }

        protected static function boot()
        {
            parent::boot();
            static::created(function ($data) { CrudMeta::created($data); });
            static::updating(function ($data) { CrudMeta::updating($data); });
        }
    }
