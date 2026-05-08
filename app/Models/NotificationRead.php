<?php
require_once('app/Models/Model.php');

class NotificationRead extends Model
{
    protected $table = 'notification_reads';
    protected $fillable = [
        'id',
        'notification_id',
        'employee_id',
        'read_at',
        'created_at'
    ];
    protected $primaryKey = 'id';
}

