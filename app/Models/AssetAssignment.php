<?php
require_once('app/Models/Model.php');

class AssetAssignment extends Model
{
    protected $table = 'asset_assignments';
    protected $fillable = [
        'id', 'asset_id', 'employee_id', 'assigned_by', 'assign_date',
        'return_date', 'condition_out', 'condition_in', 'note',
        'created_at', 'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `asset_assignments` ORDER BY assign_date DESC";
        return $this->getAll($sql);
    }
}