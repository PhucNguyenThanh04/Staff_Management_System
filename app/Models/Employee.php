<?php 

require_once('app/Models/Model.php');

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'id',
        'department_id',
        'name',
        'email',
        'password',
        'role',
        'birthday',
        'address',
        'phone_number',
        'gender',
        'cccd',
        'position',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'id';

    public function attempt($data)
    {
        $email = $data['email'];
        $password = md5($data['password']);
        $sql = "SELECT * FROM {$this->table} WHERE email = '$email' AND password = '$password'";
        return $this->getFirst($sql);
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->getAll($sql);
    }

    public function emailExists($email)
    {
        $email = trim($email);
        $sql = "SELECT * FROM {$this->table} WHERE email = '$email'";
        return $this->getFirst($sql);
    }

}
