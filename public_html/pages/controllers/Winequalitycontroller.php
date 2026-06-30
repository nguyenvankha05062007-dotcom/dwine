<?php
$result = null;
$error = null;

function predictWineQuality($inputData, $modelData) {
    $featureNames = $modelData['feature_names'];
    $trees = $modelData['trees'];
    $threshold = $modelData['threshold'];

    $row = [];
    foreach ($featureNames as $name) {
        $row[] = (float)$inputData[$name];
    }

    $sumProba = 0.0;
    $nTrees = count($trees);

    foreach ($trees as $tree) {
        $node = 0;
        while ($tree['feature'][$node] != -2) {
            $featureIndex = $tree['feature'][$node];
            $featureValue = $row[$featureIndex];
            $thresholdVal = $tree['threshold'][$node];

            if ($featureValue <= $thresholdVal) {
                $node = $tree['children_left'][$node];
            } else {
                $node = $tree['children_right'][$node];
            }
        }
        $sumProba += $tree['proba1'][$node];
    }

    $avgProba = $sumProba / $nTrees;
    $label = $avgProba >= $threshold ? 1 : 0;

    return [
        "success" => true,
        "label" => $label,
        "probability" => round($avgProba, 4),
        "threshold" => $threshold
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = [
        "fixed acidity" => $_POST['fixed_acidity'] ?? '',
        "volatile acidity" => $_POST['volatile_acidity'] ?? '',
        "citric acid" => $_POST['citric_acid'] ?? '',
        "residual sugar" => $_POST['residual_sugar'] ?? '',
        "chlorides" => $_POST['chlorides'] ?? '',
        "free sulfur dioxide" => $_POST['free_sulfur_dioxide'] ?? '',
        "total sulfur dioxide" => $_POST['total_sulfur_dioxide'] ?? '',
        "density" => $_POST['density'] ?? '',
        "pH" => $_POST['pH'] ?? '',
        "sulphates" => $_POST['sulphates'] ?? '',
        "alcohol" => $_POST['alcohol'] ?? '',
    ];

    $modelPath = __DIR__ . '/../ml/wine_model.json';
    $modelData = json_decode(file_get_contents($modelPath), true);

    try {
        $result = predictWineQuality($inputData, $modelData);
    } catch (Exception $e) {
        $error = 'Lỗi dự đoán: ' . $e->getMessage();
    }
}

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/quality/check.php';
require_once __DIR__ . '/../views/layouts/footer.php';
?>