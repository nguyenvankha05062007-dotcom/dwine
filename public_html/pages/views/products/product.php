</div> 

<div class="container mt-4">

    <div class="row">

        <button class="btn btn-wine w-100 mb-3 d-md-none" type="button"
                onclick="document.getElementById('filterSidebar').classList.toggle('show-mobile')">
            <i class="bi bi-funnel-fill me-2"></i>Bộ lọc sản phẩm
        </button>

        <div class="col-md-3 mb-4" id="filterSidebar">
            <form method="GET" action="index.php" id="filterForm">
                <input type="hidden" name="page" value="products">
                
                <?php if ($filters['category_id']): ?>
                    <input type="hidden" name="category" value="<?= $filters['category_id'] ?>">
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-uppercase fw-bold m-0" style="font-family: 'Oswald', sans-serif; color: var(--text-light);">
                        <i class="bi bi-funnel-fill text-danger me-1"></i> Bộ lọc sản phẩm
                    </h5>
                    <?php if(!empty($filters['origins']) || !empty($filters['volumes']) || !empty($filters['alcohol_ranges']) || !empty($filters['status'])): ?>
                        <a href="?page=products<?= $filters['category_id'] ? '&category='.$filters['category_id'] : '' ?>" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">Xóa bộ lọc</a>
                    <?php endif; ?>
                </div>

                <div class="card mb-3 p-3" style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
                    <h6 class="fw-bold mb-2 text-danger" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Tình Trạng</h6>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" name="status[]" value="instock" id="status_instock"
                               <?= in_array('instock', $filters['status']) ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label text-light small" for="status_instock">Còn hàng</label>
                    </div>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" name="status[]" value="outofstock" id="status_outofstock"
                               <?= in_array('outofstock', $filters['status']) ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label text-light small" for="status_outofstock">Hết hàng</label>
                    </div>
                </div>

                <div class="card mb-3 p-3" style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
                    <h6 class="fw-bold mb-2 text-danger" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Xuất Xứ</h6>
                    <div style="max-height: 180px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($allOrigins as $origin): ?>
                            <div class="form-check my-1">
                                <input class="form-check-input" type="checkbox" name="origins[]" value="<?= htmlspecialchars($origin) ?>" id="origin_<?= md5($origin) ?>"
                                       <?= in_array($origin, $filters['origins']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label text-light small" for="origin_<?= md5($origin) ?>">
                                    <?= htmlspecialchars($origin) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card mb-3 p-3" style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
                    <h6 class="fw-bold mb-2 text-danger" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Độ Mạnh</h6>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" name="alcohol_ranges[]" value="low" id="alc_low"
                               <?= in_array('low', $filters['alcohol_ranges']) ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label text-light small" for="alc_low">Nhẹ (5% - 20%)</label>
                    </div>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" name="alcohol_ranges[]" value="medium" id="alc_medium"
                               <?= in_array('medium', $filters['alcohol_ranges']) ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label text-light small" for="alc_medium">Vừa (20% - 35%)</label>
                    </div>
                    <div class="form-check my-1">
                        <input class="form-check-input" type="checkbox" name="alcohol_ranges[]" value="high" id="alc_high"
                               <?= in_array('high', $filters['alcohol_ranges']) ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label text-light small" for="alc_high">Mạnh (Trên 35%)</label>
                    </div>
                </div>

                <div class="card mb-3 p-3" style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
                    <h6 class="fw-bold mb-2 text-danger" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Dung Tích (ml)</h6>
                    <?php foreach ($allVolumes as $vol): ?>
                        <div class="form-check my-1">
                            <input class="form-check-input" type="checkbox" name="volumes[]" value="<?= $vol ?>" id="vol_<?= $vol ?>"
                                   <?= in_array($vol, $filters['volumes']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label text-light small" for="vol_<?= $vol ?>"><?= $vol ?> ml</label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="card mb-3 p-3" style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
                    <h6 class="fw-bold mb-3 text-danger" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Giá Tiền</h6>
                    
                    <div class="d-flex justify-content-between text-light small mb-2" style="font-family: 'Inter', sans-serif;">
                        <span id="minPriceDisplay">0 đ</span>
                        <span id="maxPriceDisplay">0 đ</span>
                    </div>

                    <div class="price-slider-container position-relative mt-2 mb-3" style="height: 5px; background: #333; border-radius: 5px;">
                        
                        <div id="sliderTrack" style="position: absolute; height: 100%; background-color: var(--accent-red); border-radius: 5px; top: 0; z-index: 0;"></div>

                        <input type="range" name="min_price" id="minPriceInput" 
                               min="0" max="<?= $maxPriceDB ?>" step="10000" 
                               value="<?= $filters['min_price'] ?>" style="z-index: 1;">
                        
                        <input type="range" name="max_price" id="maxPriceInput" 
                               min="0" max="<?= $maxPriceDB ?>" step="10000" 
                               value="<?= $filters['max_price'] ?>" style="z-index: 1;">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-9">
            <div class="premium-category-nav">
                <a href="?page=products" class="premium-category-link <?= !$filters['category_id'] ? 'active' : '' ?>">
                    Tất cả
                </a>
                
                <?php foreach ($categories as $cat): ?>
                    <?php 
                        // Giữ lại các bộ lọc đang có (nếu có) khi người dùng click chuyển danh mục
                        $query_string = http_build_query(array_merge($_GET, ['category' => $cat['id']])); 
                    ?>
                    <a href="?<?= $query_string ?>" class="premium-category-link <?= ($filters['category_id'] == $cat['id']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <?php if (count($products) > 0): ?>
                    <p class="text-light m-0" style="font-size: 1rem; letter-spacing: 0.5px;">
                        Đã tìm thấy <span class="fw-bold text-warning" style="font-size: 1.15rem; text-shadow: 0 0 4px rgba(255,193,7,0.2);"><?= count($products) ?></span> sản phẩm.
                    </p>
                <?php else: ?>
                    <p class="fw-bold m-0" style="font-size: 1.1rem; color: #c8102e; letter-spacing: 0.5px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Không tìm thấy sản phẩm phù hợp.
                    </p>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php if (empty($products)): ?>
                    <div class="col-12 text-center py-4">
                        <i class="bi bi-search text-secondary display-1 d-block mb-4" style="opacity: 0.5;"></i>
                        <a href="?page=products" class="btn btn-outline-danger btn-sm px-4">Cài lại bộ lọc</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 position-relative">
                                <?php if ($product['stock'] <= 0): ?>
                                    <span class="badge bg-secondary position-absolute top-0 end-0 m-2" style="z-index: 2;">Hết hàng</span>
                                <?php endif; ?>

                               <img src="/images/<?= htmlspecialchars($product['image']) ?>" 
                                    class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"
                                    style="height: 200px; object-fit: contain; background-color: #ffffff; padding: 10px; opacity: <?= $product['stock'] <= 0 ? '0.6' : '1' ?>;">
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h5>

                                    <p class="card-text text-danger fw-bold mb-3" style="font-size: 1.1rem;"><?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                                    <a href="?page=detail&id=<?= $product['id'] ?>" class="btn btn-wine">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/filter.js"></script>