<?php include __DIR__ . '/../layouts/admin/header.php'; ?>

<a href="index.php?page=admin&controller=theloai&action=index" class="btn-home">← Quay lại</a>

<h1 class="form-title">Thêm Thể loại Mới</h1>

<div class="user-form">
    <form method="POST" action="index.php?page=admin&controller=theloai&action=add">
        <table>
            <tr>
                <td>Tên thể loại:</td>
                <td>
                    <input 
                        type="text" 
                        name="ten_theloai" 
                        placeholder="Nhập tên thể loại" 
                        required
                        value="<?php echo isset($_POST['ten_theloai']) ? htmlspecialchars($_POST['ten_theloai']) : ''; ?>"
                    >
                    <?php if(isset($errors['ten_theloai'])): ?>
                        <div class="error-text"><?php echo $errors['ten_theloai']; ?></div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <div class="form-actions">
            <button type="submit">Thêm thể loại</button>
            <a href="index.php?page=admin&controller=theloai&action=index" class="btn-back">Hủy</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/admin/footer.php'; ?>