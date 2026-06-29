<h2 class="section-title">Kiểm tra chất lượng rượu vang (AI)</h2>
<p class="text-white mb-4">Nhập các chỉ số hóa học của mẫu rượu để dự đoán chất lượng bằng model Random Forest đã huấn luyện.</p>
<div class="row">
    <div class="col-md-6">
        <form method="POST" class="card p-4">
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Fixed acidity</label>
                    <input type="number" step="0.01" name="fixed_acidity" class="form-control" required value="<?= htmlspecialchars($_POST['fixed_acidity'] ?? '7.4') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Volatile acidity</label>
                    <input type="number" step="0.01" name="volatile_acidity" class="form-control" required value="<?= htmlspecialchars($_POST['volatile_acidity'] ?? '0.7') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Citric acid</label>
                    <input type="number" step="0.01" name="citric_acid" class="form-control" required value="<?= htmlspecialchars($_POST['citric_acid'] ?? '0.0') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Residual sugar</label>
                    <input type="number" step="0.01" name="residual_sugar" class="form-control" required value="<?= htmlspecialchars($_POST['residual_sugar'] ?? '1.9') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Chlorides</label>
                    <input type="number" step="0.001" name="chlorides" class="form-control" required value="<?= htmlspecialchars($_POST['chlorides'] ?? '0.076') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Free sulfur dioxide</label>
                    <input type="number" step="0.1" name="free_sulfur_dioxide" class="form-control" required value="<?= htmlspecialchars($_POST['free_sulfur_dioxide'] ?? '11') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Total sulfur dioxide</label>
                    <input type="number" step="0.1" name="total_sulfur_dioxide" class="form-control" required value="<?= htmlspecialchars($_POST['total_sulfur_dioxide'] ?? '34') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Density</label>
                    <input type="number" step="0.0001" name="density" class="form-control" required value="<?= htmlspecialchars($_POST['density'] ?? '0.9978') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">pH</label>
                    <input type="number" step="0.01" name="pH" class="form-control" required value="<?= htmlspecialchars($_POST['pH'] ?? '3.51') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Sulphates</label>
                    <input type="number" step="0.01" name="sulphates" class="form-control" required value="<?= htmlspecialchars($_POST['sulphates'] ?? '0.56') ?>">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Alcohol</label>
                    <input type="number" step="0.1" name="alcohol" class="form-control" required value="<?= htmlspecialchars($_POST['alcohol'] ?? '9.4') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-wine w-100">Dự đoán chất lượng</button>
        </form>
    </div>

    <div class="col-md-6">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($result): ?>
            <div class="card p-4 text-center">
                <h4 class="mb-3">Kết quả dự đoán</h4>
                <div class="mb-3" style="font-size: 3rem;">
                    <?= $result['label'] == 1 ? '🍷✅' : '🍷⚠️' ?>
                </div>
                <h3 class="<?= $result['label'] == 1 ? 'text-success' : 'text-warning' ?>">
    <?= $result['label'] == 1 ? 'Chất lượng cao' : 'Chất lượng thấp' ?>
</h3>
                <p class="text-white">Xác suất chất lượng cao: <?= round($result['probability'] * 100, 1) ?>%</p>
                <p class="text-white small">(Ngưỡng quyết định: <?= $result['threshold'] ?>)</p>
            </div>
        <?php else: ?>
          <div class="card p-4 text-center" style="color: var(--accent-red);">
           Nhập thông số bên trái rồi bấm "Dự đoán chất lượng" để xem kết quả.
          </div>
        <?php endif; ?>
    </div>
</div>