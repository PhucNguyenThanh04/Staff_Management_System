<?php

require_once('app/Models/Model.php');

class PayrollDetail extends Model
{
    protected $table = 'payroll_details';
    protected $fillable = [
        'id', 'employee_id', 'payroll_id', 'salary', 'base_salary', 'bonus', 'deductions', 'insurance',
        'net_salary', 'payment_date', 'created_at', 'updated_at', 'payroll_month'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `payroll_details` ORDER BY payment_date DESC";
        return $this->getAll($sql);
    }
}
