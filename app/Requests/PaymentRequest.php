<?php 
require_once('app/Requests/BaseRequest.php');

class PaymentRequest extends BaseRequest 
{
    public function validateCreate($data) {
        if (empty($data['name'])) {
            $this->errors['name'] = "Vui lòng nhập tên người đặt";
        }
        if (empty($data['phone'])) {
            $this->errors['phone'] = "Vui lòng nhập số điện thoại người đặt";
        }
        if (empty($data['email'])) {
            $this->errors['email'] = "Vui lòng nhập email người đặt";
        }
        if (isset($data['is_one'])) {

        } else {
            if (empty($data['other_name'])) {
                $this->errors['other_name'] = "Vui lòng nhập tên người nhận";
            }
            if (empty($data['other_phone'])) {
                $this->errors['other_phone'] = "Vui lòng nhập số điện thoại người nhận";
            }
            if (empty($data['other_email'])) {
                $this->errors['other_email'] = "Vui lòng nhập email người nhận";
            }
        }
        if (empty($data['address'])) {
            $this->errors['address'] = "Vui lòng nhập địa chỉ";
        }
        if (empty($data['tinh'])) {
            $this->errors['tinh'] = "Vui lòng nhập tỉnh thành";
        }
        if (empty($data['quan'])) {
            $this->errors['quan'] = "Vui lòng nhập quận huyện";
        }

        if (empty($data['date_send'])) {
            $this->errors['date_send'] = "Vui lòng nhập ngày giao hàng";
        }
        return $this->errors;
    }
}
