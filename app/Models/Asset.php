<?php
require_once('app/Models/Model.php');

class Asset extends Model
{
    protected $table = 'assets';
    protected $fillable = [
        'id', 'code', 'name', 'category', 'brand', 'model',
        'serial_number', 'value', 'purchase_date', 'status', 'note',
        'created_at', 'updated_at'
    ];
    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `assets` ORDER BY created_at DESC";
        return $this->getAll($sql);
    }
}