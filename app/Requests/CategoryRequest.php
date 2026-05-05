<?php 
require_once('app/Requests/BaseRequest.php');
require_once('app/Models/User.php');

class CategoryRequest extends BaseRequest 
{
    public function validateCreate($data) {
        if (empty($data['title'])) {
            $this->errors['title'] = "Tiêu đề không được để trống";
        }
        if (empty($data['description'])) {
            $this->errors['description'] = "Mô tả không được để trống";
        }
        return $this->errors;
    }

    public function validateUpdate($data) {
        if (empty($data['title'])) {
            $this->errors['title'] = "Tiêu đề không được để trống";
        }
        if (empty($data['description'])) {
            $this->errors['description'] = "Mô tả không được để trống";
        }
        return $this->errors;
    }

    public function validateStatus($data) {
        if (!isset($data['id'])) {
            $this->errors['id'] = "Thiếu id";
        }
        if (!isset($data['active'])) {
            $this->errors['active'] = "Thiếu active";
        }
        return $this->errors;
    }
}
