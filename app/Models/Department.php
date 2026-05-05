<?php 

require_once('app/Models/Model.php');

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT * FROM `departments` ORDER BY created_at DESC";
        return $this->getAll($sql);
    }

}
