import sys
import json
import os
import joblib
import pandas as pd

MODEL_PATH = os.path.join(os.path.dirname(__file__), "wine_quality_model.pkl")

def main():
   try:
        input_file = sys.argv[1]
        with open(input_file, 'r', encoding='utf-8') as f:
            input_data = json.load(f)

        package = joblib.load(MODEL_PATH)
        model = package["model"]
        threshold = package["threshold"]
        feature_names = package["feature_names"]

        # --- ĐOẠN SỬA ĐỔI CHUẨN XÁC NẰM Ở ĐÂY ---
        
        # 1. Chuyển đổi dữ liệu từ PHP và ép kiểu float an toàn (Xử lý cả dấu phẩy)
        raw_dict = {name: float(str(input_data[name]).replace(',', '.')) for name in feature_names}
        
        # 2. Kiểm tra xem model có lưu danh sách tên cột gốc lúc train không (feature_names_in_)
        if hasattr(model, "feature_names_in_"):
            correct_features = list(model.feature_names_in_)
            # Sắp xếp các chỉ số hóa học theo ĐÚNG THỨ TỰ mà model yêu cầu lúc huấn luyện
            row = [raw_dict[col] for col in correct_features]
            columns_to_use = correct_features
        else:
            # Nếu không có, dùng thứ tự mặc định ban đầu
            row = [raw_dict[name] for name in feature_names]
            columns_to_use = feature_names

        # 3. Tạo DataFrame khớp hoàn toàn cả về TÊN CỘT lẫn THỨ TỰ CỘT để model vừa lòng
        df = pd.DataFrame([row], columns=columns_to_use)

        # 4. Tiến hành dự đoán xác suất
        proba = model.predict_proba(df)[:, 1][0]
        label = int(proba >= threshold)

        # --- HẾT ĐOẠN SỬA ĐỔI ---

        result = {
            "success": True,
            "label": label,
            "label_text": "Chat luong cao" if label == 1 else "Chat luong thap",
            "probability": round(float(proba), 4),
            "threshold": float(threshold)
        }
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"success": False, "error": str(e)}))

if __name__ == "__main__":
    main()