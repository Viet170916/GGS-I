
## Cài đặt dự án Search
1. Setup dự án backend: 
    - Cài đặt các gói cần thiết cho dự án <strong><i>composer install</i></strong>
    - Migrate BD <strong><i> php artisan migrate </i></strong>
    - Cài đặt và setup <a href="https://www.elastic.co/fr/downloads/elasticsearch">Elasticsearch</a> và thay đổi các fields cấu hình elastic trong file .env (dòng 65 - 68)
    - Giải nén file default.zip trong resources/dataset
    - Chạy lệnh <strong><i>php artisan elasticsearch:setup</i></strong> để import data vào elasticsearch
    - Chạy dự án <strong><i>php artisan serve</i></strong>
2. Setup dự án frontend (<a href="https://github.com/Viet170916/GGS-FE">Git dự án</a>):
    - Clone dự án
    - Cài đặt các gói cần thiết cho dự án <strong style="color:#FFDAC1"><i>yarn install</i></strong> hoặc <strong><i>npm i</i></strong>
    - Chạy dự án <strong><i style="color:#FFDAC1">yarn dev</i></strong> hoặc <strong><i>npm run dev</i></strong>
