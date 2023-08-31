<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateCampoRequerido implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $table, $column, $valor, $conditionId;

    public function __construct($table, $column, $valor, $conditionId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->valor = $valor;
        $this->conditionId = $conditionId;
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
        // $query = DB::table($this->table)
        //     ->where('delete', 0)
        //     ->where('id', $this->conditionId);

        // return $query->equipamentrequire === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
