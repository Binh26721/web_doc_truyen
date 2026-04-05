<?php include __DIR__ . '/../layouts/admin/header.php'; ?>

<link rel="stylesheet" href="/web_doc_truyen/frontend/public/css/theloai.css">

<div class="theloai-container">
    <a href="index.php?page=admin&controller=home" class="btn-home">← Trang chủ</a>

    <h1 class="page-title">Quản lý Thể loại</h1>
    
    <a href="index.php?page=admin&controller=theloai&action=add" class="btn-add">Thêm thể loại mới</a>
    
    <!-- Form tìm kiếm -->
    <form method="GET" action="index.php" class="search-form">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="controller" value="theloai">
        <input type="hidden" name="action" value="index">

        <div class="search-wrapper">
            <input type="text"
                   name="keyword"
                   class="search-input"
                   placeholder="🔍 Tìm theo tên thể loại..."
                   value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">

            <button type="submit" class="btn-search">
                Tìm kiếm 
            </button>

            <?php if (!empty($_GET['keyword'])): ?>
                <a href="index.php?page=admin&controller=theloai" class="btn-clear">
                    ✖
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Bảng danh sách thể loại -->
    <table class="theloai-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thể loại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($theloai)): ?>
                <tr>
                    <td colspan="3" class="empty-data">
                        Chưa có thể loại nào
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($theloai as $tl): ?>
                    <tr>
                        <td><?php echo $tl['id']; ?></td>
                        <td><?php echo htmlspecialchars($tl['ten_theloai']); ?></td>
                        <td class="action-cell">
                            <a href="index.php?page=admin&controller=theloai&action=edit&id=<?php echo $tl['id']; ?>" 
                               class="btn-edit">
                                Sửa
                            </a>
                            <a href="index.php?page=admin&controller=theloai&action=delete&id=<?php echo $tl['id']; ?>" 
                               class="btn-delete"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại <?php echo htmlspecialchars($tl['ten_theloai']); ?>?')">
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/admin/footer.php'; ?>