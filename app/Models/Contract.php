<?php

require_once('app/Models/Model.php');

class Contract extends Model
{
    protected $table = 'contracts';
    protected $fillable = [
        'id', 'name', 'employee_id', 'contract_type', 
        'start_date', 'end_date', 'created_at', 'updated_at'
    ];
    protected $primaryKey = 'id';

    /**
     * Lấy tất cả các hợp đồng, sắp xếp theo ngày bắt đầu (mới nhất trước).
     *
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM `contracts` ORDER BY start_date DESC";
        return $this->getAll($sql);
    }
}
