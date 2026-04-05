<?php
require_once __DIR__ . '/../model/TrangModel.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class TrangController {
    private $model;

    public function __construct() {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkAdmin();
        $this->model = new TrangModel();
    }

    private function normalizeTrangImagePath($imagePath) {
        $imagePath = trim((string)$imagePath);
        if ($imagePath === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $imagePath)) {
            return $imagePath;
        }

        $normalized = ltrim(str_replace('\\', '/', $imagePath), '/');
        if (strpos($normalized, 'web_doc_truyen/') === 0) {
            $normalized = substr($normalized, strlen('web_doc_truyen/'));
        }

        $legacyPrefixes = [
            'uploads/trang/',
            'frontend/public/uploads/trang/',
            'frontend/images/trang/',
            'images/trang/',
            'backend/uploads/trang/'
        ];

        foreach ($legacyPrefixes as $prefix) {
            if (strpos($normalized, $prefix) === 0) {
                $fileName = basename($normalized);
                if ($fileName !== '' && $fileName !== '.' && $fileName !== '..') {
                    return 'uploads/trang/' . $fileName;
                }
                return '';
            }
        }

        $prefixes = [
            'backend/',
            'frontend/public/'
        ];

        foreach ($prefixes as $prefix) {
            if (strpos($normalized, $prefix) === 0) {
                $normalized = substr($normalized, strlen($prefix));
                break;
            }
        }

        return $normalized;
    }

    private function deleteTrangImageFile($imagePath) {
        $normalized = $this->normalizeTrangImagePath($imagePath);
        if ($normalized === '' || preg_match('#^https?://#i', $normalized)) {
            return;
        }

        $fileName = basename($normalized);
        $candidates = [
            __DIR__ . '/../' . $normalized,
            __DIR__ . '/../../frontend/public/' . $normalized,
            __DIR__ . '/../../frontend/images/trang/' . $fileName
        ];

        foreach ($candidates as $candidate) {
            $fullPath = realpath($candidate);
            if ($fullPath && file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    // Upload ảnh (giống TruyenController)
    private function uploadImage($file, $subFolder = '') {
        if (empty($file['name'])) return null;
        
        $target_dir = __DIR__ . "/../uploads/";
        if ($subFolder) {
            $target_dir .= $subFolder . '/';
        }
        
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $newFileName;
        
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return null;
        }
        
        if ($file["size"] > 5000000) {
            return null;
        }
        
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return null;
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "uploads/" . ($subFolder ? $subFolder . "/" : "") . $newFileName;
        }
        
        return null;
    }

    // Danh sách
   public function index() {
    $id_chuong = $_GET['id_chuong'] ?? 0;
    $keyword   = trim($_GET['keyword'] ?? '');

    if ($keyword !== '' && is_numeric($keyword)) {
      
        $trang = $this->model->searchBySoTrang($id_chuong, (int)$keyword);
    } else {
        
        $trang = $this->model->getByChuong($id_chuong);
    }

    require_once __DIR__ . '/../../frontend/view/trang/list.php';
    }



    // Thêm
    public function add() {
    $id_chuong = $_GET['id_chuong'] ?? 0;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $so_trang = (int)$_POST['so_trang'];
        $noi_dung = trim($_POST['noi_dung'] ?? '');
        $loai     = $_POST['loai'] ?? 'text';

        $hasText  = $noi_dung !== '';
        $hasImage = !empty($_FILES['anh']['name']);

        if ($so_trang <= 0) {
            $errors['so_trang'] = 'Số trang không hợp lệ';
        }
        elseif ($this->model->existsSoTrang($id_chuong, $so_trang)) {
            $errors['so_trang'] = 'Trang này đã tồn tại trong chương';
        }
        elseif ($loai === 'text' && (!$hasText || $hasImage)) {
            $errors['general'] = 'Trang văn bản chỉ được nhập nội dung, không được chọn ảnh';
        }
        elseif ($loai === 'image' && (!$hasImage || $hasText)) {
            $errors['general'] = 'Trang ảnh chỉ được chọn ảnh, không được nhập nội dung';
        }

        // ===== XỬ LÝ KHI KHÔNG LỖI =====
        if (empty($errors)) {

            $anh = '';
            if ($loai === 'image') {
                $anh = $this->uploadImage($_FILES['anh'], 'trang');
                if (!$anh) {
                    $errors['general'] = 'Upload ảnh thất bại hoặc ảnh không hợp lệ';
                }
            }

            if (empty($errors)) {
                $this->model->insert([
                    'id_chuong' => $id_chuong,
                    'so_trang'  => $so_trang,
                    'noi_dung'  => $loai === 'text' ? $noi_dung : '',
                    'anh'       => $loai === 'image' ? $anh : '',
                    'loai'      => $loai
                ]);

                header("Location: index.php?page=admin&controller=trang&id_chuong=$id_chuong&success=add");
                exit;
            }
        }
    }

    require_once __DIR__ . '/../../frontend/view/trang/add.php';
}

    // Sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $trang = $this->model->getById($id);

        if (!$trang) {
            header("Location: index.php?page=admin");
            exit;
        }

        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $so_trang = (int)$_POST['so_trang'];
            $noi_dung = trim($_POST['noi_dung'] ?? '');
            $loai = $_POST['loai'] ?? 'text';
            
            // Giữ ảnh cũ
            $anh = $this->normalizeTrangImagePath($trang['anh']);
            
            // Upload ảnh mới nếu có
            $new_anh = $this->uploadImage($_FILES['anh'], 'trang');
            if ($new_anh) {
                if (!empty($anh) && $anh !== $new_anh) {
                    $this->deleteTrangImageFile($anh);
                }
                $anh = $new_anh;
                $loai = 'image';
            }

            if ($so_trang <= 0) {
                $errors['so_trang'] = 'Số trang không hợp lệ';
            }
            elseif ($this->model->existsSoTrangExceptId($trang['id_chuong'], $so_trang, $id)) {
                $errors['so_trang'] = 'Trang này đã tồn tại';
            }
            else {
                $this->model->update($id, [
                    'so_trang' => $so_trang,
                    'noi_dung' => $noi_dung,
                    'anh' => $anh,
                    'loai' => $loai
                ]);
                // ===== THÊM success=edit =====
                header("Location: index.php?page=admin&controller=trang&id_chuong=" . $trang['id_chuong'] . "&success=edit");
                exit;
            }
        }

        require_once __DIR__ . '/../../frontend/view/trang/edit.php';
    }

    // Xóa
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $trang = $this->model->getById($id);
        if ($trang) {
            // Xóa file ảnh nếu có
            if (!empty($trang['anh'])) {
                $this->deleteTrangImageFile($trang['anh']);
            }
            
            $this->model->delete($id);
            // ===== THÊM success=delete =====
            header("Location: index.php?page=admin&controller=trang&id_chuong=" . $trang['id_chuong'] . "&success=delete");
            exit;
        }
    }
}