<?php include __DIR__ . '/../layouts/admin/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/page_image_helper.php'; ?>

<!-- CSS RIÊNG CHO TRANG -->
<link rel="stylesheet" href="../public/css/trang.css">

<div class="admin-container">

    <!-- BACK -->
    <a href="index.php?page=admin&controller=chuong&id_chuong=<?= $id_chuong ?>"
       class="btn-back">
        Quay lại Chương
    </a>

    <!-- TITLE -->
    <div class="trang-header">
        <h2>Danh sách Trang</h2>
    </div>

    <!-- ALERT -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-message alert-success">
            <?php
                if ($_GET['success'] === 'add') echo "Thêm trang thành công!";
                if ($_GET['success'] === 'edit') echo "Cập nhật trang thành công!";
                if ($_GET['success'] === 'delete') echo "Xóa trang thành công!";
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert-message alert-error">
            Có lỗi xảy ra! Vui lòng thử lại.
        </div>
    <?php endif; ?>

   <a href="index.php?page=admin&controller=trang&action=add&id_chuong=<?= $id_chuong ?>"
   class="btn-add">
    Thêm trang mới
</a>

    <!-- SEARCH -->
    <form method="GET" action="index.php" class="trang-search-form">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="controller" value="trang">
        <input type="hidden" name="action" value="index">
        <input type="hidden" name="id_chuong" value="<?= $id_chuong ?>">

        <div class="trang-search-container">
            <input type="text"
                   name="keyword"
                   class="trang-search-input"
                   placeholder="🔍 Tìm theo số trang..."
                   value="<?= $_GET['keyword'] ?? '' ?>">

            <button type="submit" class="trang-search-button">
                Tìm kiếm
            </button>

            <?php if (!empty($_GET['keyword'])): ?>
                <a href="index.php?page=admin&controller=trang&action=index&id_chuong=<?= $id_chuong ?>"
                   class="trang-search-clear">
                    ✖
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- TABLE -->
    <?php if (empty($trang)): ?>
        <p class="empty-data">Chưa có trang nào.</p>
    <?php else: ?>
        <table class="trang-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Số trang</th>
                    <th>Loại</th>
                    <th>Xem trước</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trang as $t): ?>
                <tr>
                    <td class="trang-id"><?= $t['id'] ?></td>

                    <td class="trang-so">
                        <span class="trang-badge">
                            Trang <?= $t['so_trang'] ?>
                        </span>
                    </td>

                    <td class="trang-type-text">
                        <?= $t['loai'] === 'image' ? 'Ảnh' : 'Văn bản' ?>
                    </td>

                    <td class="trang-preview">
                        <?php if ($t['loai'] === 'image' && !empty($t['anh'])): ?>
                            <img src="<?= htmlspecialchars(resolve_page_image_url($t['anh'])) ?>"
                                 onerror="this.src='https://via.placeholder.com/120x170/2c3e50/ffffff?text=No+Image'">
                        <?php elseif ($t['loai'] === 'text'): ?>
                            <span class="text-preview">
                                <?= htmlspecialchars(mb_substr($t['noi_dung'], 0, 30)) ?>...
                            </span>
                        <?php else: ?>
                            <span class="text-empty">Trống</span>
                        <?php endif; ?>
                    </td>

                    <td class="trang-actions">
                        <a class="btn-edit"
                           href="index.php?page=admin&controller=trang&action=edit&id=<?= $t['id'] ?>">
                            Sửa
                        </a>
                        <a class="btn-delete"
                           onclick="return confirm('Xác nhận xóa trang số <?= $t['so_trang'] ?>?')"
                           href="index.php?page=admin&controller=trang&action=delete&id=<?= $t['id'] ?>">
                            Xóa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../layouts/admin/footer.php'; ?>
