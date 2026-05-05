<?php

require_once('app/Models/Model.php');

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = ['id', 'employee_id', 'base_salary', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `payrolls` ORDER BY created_at DESC";
        return $this->getAll($sql);
    }
}
