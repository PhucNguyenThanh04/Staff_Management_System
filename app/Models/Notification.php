<?php
require_once('app/Models/Model.php');

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'id',
        'title',
        'content',
        'sender_id',
        'target_type',
        'target_value',
        'is_pinned',
        'is_active',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `notifications` ORDER BY is_pinned DESC, created_at DESC";
        return $this->getAll($sql);
    }
}

