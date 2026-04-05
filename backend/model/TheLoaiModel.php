<?php
require_once __DIR__ . '/../database/myconnection.php';

class TheLoaiModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    // Lấy tất cả thể loại
    public function getAll() {
        $sql = "SELECT * FROM theloai ORDER BY id DESC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    // Tìm thể loại theo tên
    public function searchByTen($keyword) {
        $sql = "SELECT * FROM theloai WHERE ten_theloai LIKE ? ORDER BY ten_theloai ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        $like = '%' . $keyword . '%';
        mysqli_stmt_bind_param($stmt, "s", $like);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }


    // Lấy 1 thể loại theo ID
    public function getById($id) {
        $sql = "SELECT * FROM theloai WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // ✅ THÊM THỂ LOẠI
    public function insert($ten) {
        $sql = "INSERT INTO theloai (ten_theloai) VALUES (?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $ten);
        return mysqli_stmt_execute($stmt);
    }

    // ✅ KIỂM TRA TRÙNG (ADD)
    public function existsByName($ten) {
        $sql = "SELECT id FROM theloai WHERE ten_theloai = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $ten);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    // ✅ KIỂM TRA TRÙNG (EDIT)
    public function existsByNameExceptId($ten, $id) {
        $sql = "SELECT id FROM theloai WHERE ten_theloai = ? AND id != ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $ten, $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    // Cập nhật thể loại
    public function update($id, $ten) {
        $sql = "UPDATE theloai SET ten_theloai = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $ten, $id);
        return mysqli_stmt_execute($stmt);
    }

    // Xóa thể loại
    public function delete($id) {
        $sql = "DELETE FROM theloai WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
}
