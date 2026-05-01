<?php

require_once('app/Models/Model.php');

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = [
        'id',
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'working_hours',
        'work_point',
        'note',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `attendances` ORDER BY attendance_date DESC";
        return $this->getAll($sql);
    }
}
