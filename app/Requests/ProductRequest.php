<?php 
require_once('app/Requests/BaseRequest.php');
require_once('app/Models/User.php');

class ProductRequest extends BaseRequest 
{
    public function validateCreate($data) {
        if (empty($data['title'])) {
            $this->errors['title'] = "Tiêu đề không được để trống";
        }
        if (empty($data['category_id'])) {
            $this->errors['category_id'] = "Danh mục không được để trống";
        }
        if (empty($data['price'])) {
            $this->errors['price'] = "Giá không được để trống";
        }
        return $this->errors;
    }

    public function validateUpdate($data) {
        if (empty($data['title'])) {
            $this->errors['title'] = "Tiêu đề không được để trống";
        }
        if (empty($data['category_id'])) {
            $this->errors['category_id'] = "Danh mục không được để trống";
        }
        if (empty($data['price'])) {
            $this->errors['price'] = "Giá không được để trống";
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
