<?php
$pageTitle = "Thể loại: " . htmlspecialchars($tenTheLoai);
require_once __DIR__ . '/../layouts/cover_image_helper.php';
include __DIR__ . '/../layouts/user/header.html';
?>



<!-- DANH SÁCH TRUYỆN -->
<section class="stories">
    <div class="container">
        <h2>📖 Tìm thấy <?= count($truyens) ?> truyện</h2>

        <div class="story-grid">
            <?php if (empty($truyens)): ?>
                <div style="grid-column:1/-1; text-align:center; padding:40px;">
                    <h3>📚 Chưa có truyện</h3>
                    <p>Thể loại này hiện chưa có truyện nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($truyens as $truyen): ?>
                    <div class="story-card">
                        <a href="/web_doc_truyen/frontend/public/index.php?page=chitiet&id=<?= $truyen['id'] ?>">
                            
                            <?php
                            $imagePath = resolve_cover_image_url(
                                $truyen['anh_bia'] ?? '',
                                'https://via.placeholder.com/180x240/667eea/ffffff?text=No+Image'
                            );
                            ?>

                            <img src="<?= $imagePath ?>"
                                 alt="<?= htmlspecialchars($truyen['ten_truyen']) ?>"
                                 onerror="this.src='https://via.placeholder.com/180x240/667eea/ffffff?text=No+Image'">

                            <h3><?= htmlspecialchars($truyen['ten_truyen']) ?></h3>

                            <p>
                                Tác giả:
                                <?php
                                if (!empty($truyen['but_danh'])) {
                                    echo htmlspecialchars($truyen['but_danh']);
                                } elseif (!empty($truyen['ten_tacgia'])) {
                                    echo htmlspecialchars($truyen['ten_tacgia']);
                                } else {
                                    echo 'Đang cập nhật';
                                }
                                ?>
                            </p>

                            <p class="story-views">
                                👁️ <?= number_format($truyen['luot_xem']) ?> lượt xem
                            </p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
