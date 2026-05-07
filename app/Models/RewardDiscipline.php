<?php
require_once('app/Models/Model.php');

class RewardDiscipline extends Model
{
    protected $table = 'rewards_disciplines';
    protected $fillable = [
        'id', 'employee_id', 'approved_by', 'rd_type', 'title',
        'reason', 'amount', 'effective_date', 'status',
        'created_at', 'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `rewards_disciplines` ORDER BY effective_date DESC";
        return $this->getAll($sql);
    }
}