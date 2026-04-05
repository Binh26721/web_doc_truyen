<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thể loại</title>
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>

    <a href="index.php?page=admin&controller=theloai&action=index" class="btn-home">← Quay lại</a>

    <h1 class="form-title">Sửa Thể loại</h1>

    <div class="user-form">
        <form method="POST">
            <table>
                <tr>
                    <td>Tên thể loại:</td>
                    <td>
                        <input
                            type="text"
                            name="ten_theloai"
                            value="<?= htmlspecialchars($theloai['ten_theloai']) ?>"
                            required
                        >
                    </td>
                </tr>
            </table>

            <div class="form-actions">
                <button type="submit">Cập nhật</button>
                <a href="index.php?page=admin&controller=theloai&action=index" class="btn-back">Hủy</a>
            </div>
        </form>
    </div>

</body>
</html>
