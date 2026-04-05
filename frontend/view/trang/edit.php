<?php include __DIR__ . '/../layouts/admin/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/page_image_helper.php'; ?>
<link rel="stylesheet" href="/web_doc_truyen/frontend/public/css/trang.css">


<a href="index.php?page=admin&controller=trang&id_chuong=<?= $trang['id_chuong'] ?>"
   class="btn-home">
    ← Quay lại
</a>

<h1 class="form-title">Sửa Trang</h1>

<?php if (isset($errors['general'])): ?>
    <div class="alert-message alert-error">
        <?= htmlspecialchars($errors['general']) ?>
    </div>
<?php endif; ?>

<div class="user-form trang-add-form">
    <form method="POST" enctype="multipart/form-data">
        <table>

            <!-- SỐ TRANG -->
            <tr>
                <td>Trang số: <span class="required">*</span></td>
                <td>
                    <input type="number"
                           name="so_trang"
                           min="1"
                           value="<?= $trang['so_trang'] ?>"
                           required>
                    <?php if (isset($errors['so_trang'])): ?>
                        <div class="error-text"><?= $errors['so_trang'] ?></div>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- LOẠI -->
            <tr>
                <td>Loại: <span class="required">*</span></td>
                <td>
                    <select name="loai" required>
                        <option value="text" <?= $trang['loai'] === 'text' ? 'selected' : '' ?>>
                            Văn bản
                        </option>
                        <option value="image" <?= $trang['loai'] === 'image' ? 'selected' : '' ?>>
                            Ảnh
                        </option>
                    </select>
                </td>
            </tr>

            <!-- NỘI DUNG -->
            <tr>
                <td>Nội dung:</td>
                <td>
                    <textarea name="noi_dung"
                              rows="14"
                              placeholder="Nhập nội dung trang..."><?= htmlspecialchars($trang['noi_dung']) ?></textarea>
                </td>
            </tr>

            <!-- ẢNH HIỆN TẠI -->
            <tr>
                <td>Ảnh hiện tại:</td>
                <td>
                    <?php if (!empty($trang['anh'])): ?>
                        <img src="<?= htmlspecialchars(resolve_page_image_url($trang['anh'])) ?>"
                             onerror="this.src='https://via.placeholder.com/250x350/2c3e50/ffffff?text=No+Image'"
                             style="max-width:250px;border-radius:8px;display:block;margin-bottom:10px;">
                    <?php else: ?>
                        <span style="color:#999;">Chưa có ảnh</span>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- ĐỔI ẢNH -->
            <tr>
                <td>Đổi ảnh:</td>
                <td>
                    <input type="file" name="anh" accept="image/*">
                    <small style="color:#666;">
                        Để trống nếu không đổi ảnh
                    </small>
                </td>
            </tr>

        </table>

        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a href="index.php?page=admin&controller=trang&id_chuong=<?= $trang['id_chuong'] ?>"
               class="btn-back">
                Hủy
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/admin/footer.php'; ?>
