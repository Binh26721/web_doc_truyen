<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Trang</title>

    <link rel="stylesheet" href="../public/css/admin.css">
    <link rel="stylesheet" href="../public/css/trang.css">
</head>
<body>

    <a href="index.php?page=admin&controller=trang&id_chuong=<?= $id_chuong ?>"
       class="btn-home">
        ← Quay lại
    </a>

    <h1 class="form-title">Thêm Trang Mới</h1>

    <?php if (isset($errors['general'])): ?>
        <div class="alert-message alert-error">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <div class="user-form trang-add-form">
        <form method="POST" enctype="multipart/form-data">
            <table>

                <tr>
                    <td>Trang số: <span class="required">*</span></td>
                    <td>
                        <input type="number"
                               name="so_trang"
                               min="1"
                               placeholder="VD: 1"
                               required>
                        <?php if (isset($errors['so_trang'])): ?>
                            <div class="error-text"><?= $errors['so_trang'] ?></div>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td>Loại: <span class="required">*</span></td>
                    <td>
                        <select name="loai" required>
                            <option value="text">Văn bản</option>
                            <option value="image">Ảnh</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Nội dung:</td>
                    <td>
                        <textarea name="noi_dung"
                                  rows="14"
                                  placeholder="Nhập nội dung trang..."></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Ảnh:</td>
                    <td>
                        <input type="file"
                               name="anh"
                               accept="image/*">
                    </td>
                </tr>

            </table>

            <div class="form-actions">
                <button type="submit">Thêm trang</button>
                <a href="index.php?page=admin&controller=trang&id_chuong=<?= $id_chuong ?>"
                   class="btn-back">
                    Hủy
                </a>
            </div>
        </form>
    </div>

</body>
</html>
