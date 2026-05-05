<?php 
require ('./traits/Middleware.php');

class Controller 
{
    use Middleware;
    
    public function view($view, $data = []) 
    {
        return render("$view", $data);
    }

    public function ajax($data = null, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        if ($statusCode >= 200 && $statusCode <= 300) {
            echo json_encode([
                'status' => 1,
                'data' => $data
            ]);
            return;
        }
        echo json_encode([
            'status' => 0,
            'data' => $data
        ]);
        exit;
    }
}