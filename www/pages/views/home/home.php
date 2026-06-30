
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
</div>
<div class="wine-banner">
    <div class="row align-items-center g-0">
        <div class="col-md-6 text-center wine-banner-img">
            <img src="/images/homelogo.png" alt="Wine Splash" class="img-fluid">
        </div>
        <div class="col-md-6 wine-banner-text">
            <span class="hero-eyebrow">D-Wine Collection</span>
            <h1 class="wine-banner-title">Tinh hoa chắt lọc<br> nâng tầm vị giác</h1>
            <p class="wine-banner-sub">Mỗi giọt rượu là một câu chuyện. Khám phá bộ sưu tập rượu vang chọn lọc của D-WINE.</p>
            <a href="?page=products" class="btn btn-wine">Xem thêm</a>
        </div>
    </div>
</div>
<div class="container mt-4">
<h2 class="section-title">Sản phẩm mới ra mắt</h2>
<div class="hero">
    <div class="row align-items-center">
        <!-- Cột trái: chữ cố định, không đổi theo slide -->
         <div class="col-md-4 ps-4 ps-md-5">
            <span class="hero-eyebrow">Wine Shop Legendary</span>
            <h1 class="hero-title mt-3">Wine Dreams<br>Come True</h1>
         </div>
        <!-- Cột phải: carousel lấy data từ DB -->
        <div class="col-md-8">
            <div id="productHeroCarousel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner">
                    <?php foreach ($featuredProducts as $index => $product): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="row align-items-center">
                                <div class="col-md-6 text-center">
                                    <img src="/images/<?= htmlspecialchars($product['image']) ?>"
                                         style="max-height: 280px;" alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-white"><?= htmlspecialchars($product['name']) ?></h4>
                                    <p class="text-white"><?= htmlspecialchars($product['description']) ?></p>
                                    <div class="hero-price mb-3"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>

                                    <!-- Indicators đặt ngay trong từng slide, ngay trên nút Mua ngay -->
                                    <div class="carousel-indicators mb-2">
                                        <?php foreach ($featuredProducts as $i => $p): ?>
                                            <button type="button" data-bs-target="#productHeroCarousel"
                                                    data-bs-slide-to="<?= $i ?>"
                                                    class="<?= $i === $index ? 'active' : '' ?>"></button>
                                        <?php endforeach; ?>
                                    </div>

                                    <a href="?page=detail&id=<?= $product['id'] ?>" class="btn btn-wine">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#productHeroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productHeroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<h2 class="section-title">Sản phẩm nổi bật</h2>

<div class="row">
    <?php foreach ($otherProducts as $product): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
               <img src="/images/<?= htmlspecialchars($product['image']) ?>" 
                class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"
                style="height: 200px; object-fit: contain; background-color: #fff; padding: 10px;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-danger fw-bold"><?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                    <a href="/?page=detail&id=<?= $product['id'] ?>" 
                       class="btn btn-wine">Xem chi tiết</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<section class="brands-section">
    <h2 class="section-title">Thương hiệu nổi tiếng</h2>
    <div class="row align-items-center text-center">
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/chivas12logo.png" class="brand-logo" alt="Chivas Regal">
        </div>
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/hennessy.png" class="brand-logo" alt="Hennessy">
        </div>
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/moet.png" class="brand-logo" alt="Moët & Chandon">
        </div>
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/johnniewalker.png" class="brand-logo" alt="Johnnie Walker">
        </div>
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/absolut.png" class="brand-logo" alt="Absolut Vodka">
        </div>
        <div class="col-md-2 col-4 mb-4">
            <img src="/images/brands/jacobscreek.png" class="brand-logo" alt="Jacob's Creek">
        </div>
    </div>
</section>
<section class="reviews-section" id="reviews-section">
    <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
    <div class="row">
        <?php error_reporting(E_ALL); ini_set('display_errors', 1); ?>
        <?php foreach ($reviews as $review): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="review-avatar me-2">
                               <?= strtoupper(substr($review['fullname'], 0, 1)) ?>
                            </div>
                            <h6 class="mb-0"><?= htmlspecialchars($review['fullname']) ?></h6>
                        </div>
                        <span class="text-warning">
                            <?php for ($i = 0; $i < $review['rating']; $i++): ?>★<?php endfor; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++): ?><span class="text-muted">★</span><?php endfor; ?>
                        </span>
                    </div>
                    <?php if ($review['product_name']): ?>
                        <p class="small mb-2 text-review-product">Sản phẩm: <?= htmlspecialchars($review['product_name']) ?></p>
                    <?php endif; ?>
                    <p class="mb-0"><?= htmlspecialchars($review['comment']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3" style="border-color: #2a2a2a !important;">
        <h2 class="section-title m-0" style="border-left: 4px solid var(--accent-red); padding-left: 12px; font-family: 'Oswald', sans-serif; text-transform: uppercase;">
            Khách hàng nhận xét
        </h2>

        <?php 
        // TRƯỜNG HỢP 1: NẾU ĐÃ ĐĂNG NHẬP -> HIỂN THỊ NÚT ĐÁNH GIÁ SẢN PHẨM
        if (isset($_SESSION['username'])): 
        ?>
            <a href="index.php?page=products" class="btn btn-wine d-flex align-items-center gap-2">
                <span>VIẾT ĐÁNH GIÁ NGAY</span> 🍷
            </a>
        <?php endif; ?>
    </div>

    <?php 
    if (!isset($_SESSION['username'])): 
    ?>
        <div class="p-3 mb-4 text-center rounded-3 shadow-sm" style="background: linear-gradient(to right, #121212, var(--accent-red-dark), #121212); border: 1px solid rgba(200, 16, 46, 0.3);">
            <span class="text-light fs-6">
                Bạn muốn chia sẻ cảm nhận về hương vị rượu? 
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="fw-bold text-light text-decoration-underline ms-1" style="color: #fff !important;">
                    Đăng nhập tài khoản
                </a> ngay để bình chọn số sao!      
            </span>
        </div>
    <?php endif; ?>

    <div class="row">
        </div>
</div>
</div>
<script src="/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>