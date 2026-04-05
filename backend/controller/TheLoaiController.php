<?php
require_once __DIR__ . '/../model/TheLoaiModel.php';
require_once(__DIR__ . '/../middleware/AuthMiddleware.php');

class TheLoaiController {
    private $model;

    public function __construct() {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin();
        $this->model = new TheLoaiModel();
    }

    // Danh sách
    public function index() {
    $keyword = trim($_GET['keyword'] ?? '');

    if ($keyword !== '') {
        $theloai = $this->model->searchByTen($keyword);
    } else {
        $theloai = $this->model->getAll();
    }

    require_once __DIR__ . '/../view/theloai/list.php';
    }


    // Thêm
    public function add() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = trim($_POST['ten_theloai'] ?? '');

            if ($ten === '') {
                $errors['ten_theloai'] = 'Tên thể loại không được để trống';
            }
            elseif ($this->model->existsByName($ten)) {
                $errors['ten_theloai'] = 'Thể loại này đã tồn tại';
            }
            else {
                $this->model->insert($ten);
                header("Location: index.php?page=admin&controller=theloai&action=index");
                exit;
            }
        }

        require_once __DIR__ . '/../view/theloai/add.php';
    }

    // Sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $theloai = $this->model->getById($id);

        if (!$theloai) {
            header("Location: index.php?page=admin&controller=theloai&action=index");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = trim($_POST['ten_theloai'] ?? '');

            if ($ten === '') {
                $errors['ten_theloai'] = 'Tên thể loại không được để trống';
            }
            elseif ($this->model->existsByNameExceptId($ten, $id)) {
                $errors['ten_theloai'] = 'Tên thể loại đã tồn tại';
            }
            else {
                $this->model->update($id, $ten);
                header("Location: index.php?page=admin&controller=theloai&action=index");
                exit;
            }
        }

        require_once __DIR__ . '/../view/theloai/edit.php';
    }

    // Xóa
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $this->model->delete($id);
        }
        header("Location: index.php?page=admin&controller=theloai&action=index");
        exit;
    }
}
