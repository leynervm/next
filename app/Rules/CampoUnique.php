<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CampoUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $table;
    protected $column;
    protected $softDeleted;
    protected $ignoreId;
    protected $columnOptional;
    protected $valueOptional;
    protected $mensaje;


    public function __construct($table, $column, $ignoreId = null, $softDeleted = false, $columnOptional = null, $valueOptional = null, $mensaje = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreId = $ignoreId;
        $this->softDeleted = $softDeleted;
        $this->columnOptional = $columnOptional;
        $this->valueOptional = $valueOptional;
        $this->mensaje = $mensaje;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = DB::table($this->table);

        if (is_numeric($value)) {
            $query->whereRaw($this->column . '= ?', [$value]);
        } else {
            $query->whereRaw('UPPER(' . $this->column . ') = ?', [mb_strtoupper($value, "UTF-8")]);
        }

        if (!is_null($this->valueOptional)) {
            $valueoptional = is_numeric($this->valueOptional) ? $this->valueOptional : mb_strtoupper($this->valueOptional, "UTF-8");
            $query->where($this->columnOptional, $valueoptional);
        }

        if ($this->softDeleted) {
            $query->whereNull('deleted_at');
        }

        if (!is_null($this->ignoreId)) {
            $query->where('id', '<>', $this->ignoreId);
        }

        return $query->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        if (!empty($this->mensaje)) {
            return $this->mensaje;
        }
        return __("The field :attribute exist in the table") /* . ' ' . __($this->table) */;
    }
}
