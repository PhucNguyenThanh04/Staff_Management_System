<?php
require_once('app/Models/Model.php');

class Evaluation extends Model
{
    protected $table = 'evaluations';
    protected $fillable = [
        'id', 'employee_id', 'reviewer_id', 'eval_type', 'period',
        'eval_date', 'score', 'content', 'strengths', 'weaknesses',
        'status', 'created_at', 'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `evaluations` ORDER BY eval_date DESC";
        return $this->getAll($sql);
    }
}