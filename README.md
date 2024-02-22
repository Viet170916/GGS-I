## Cài đặt dự án Search

1. Setup dự án backend:
    - Cài đặt các gói cần thiết cho dự án <strong><i>composer install</i></strong>
    - Migrate BD <strong><i> php artisan migrate </i></strong>
    - Cài đặt và setup <a href="https://www.elastic.co/fr/downloads/elasticsearch">Elasticsearch</a> và thay đổi các
      fields cấu hình elastic trong file .env (dòng 65 - 70)
      <br>
   <div style="color:#e66465">
      Chú ý:
       <ol style="color:#e66465">
        <li>
             khi chạy elasticsearch lần đầu sẽ được cung cấp password sau khi chạy lệnh khởi động elasticsearch
        </li>
        <li>
             Thay đổi đường dẫn tới chứng chỉ ca 
            (< từ thư mục gốc của elasticsearch>/config/certs/http_ca.crt) 
        </li>
       </ol>
      </div>
    - Mở crontab (<span style="background:#6f89a4">crontab -e</span>) và thêm dòng <strong ><i>< lệnh set thời gian 1 chu kỳ
      crawl > <<span style="color:#FFDAC1">Đường dẫn tới node</span>> <<span style="color:#FFDAC1">dấu
      cách</span>> <<span style="color:#FFDAC1">Đường dẫn tới file chạy crawl trong dự án (file này nằm trong dự
      án)</span> > >> <<span style="color:#FFDAC1">Đường dẫn tới file log (sử dụng file log "storage/logs/crawl.log"
      trong dự án )</span>> 2>&1</i></strong>
   
      Ví dụ đường dẫn 

    <strong style="color:#8dab8c"><i>* * * * * <span>/usr/local/bin/node /Users/vuquocviet/PhpstormProjects/GGS-I/crawler.js >> /Users/vuquocviet/PhpstormProjects/GGS-I/storage/logs/crawl.log 2>&1</span></i></strong>
      <br>
      Lệnh set thời gian:
           <ol>
               <li>Mỗi phút: * * * * * </li>
               <li>Mỗi giờ (bắt đầu từ phút thứ 0): 0 * * * * </li>
               <li>Mỗi ngày (bắt đầu từ 01:00): 0 1 * * * </li>
               <li>Thực thi vào ngày 1 của mỗi tháng lúc 02:30: 30 2 1 * * </li>
               <li>Thực thi vào ngày 1 tháng 1 hàng năm: 0 0 1 1 *</li>
           </ol>
        Lưu và đóng crontab với nano: <span style="background:#6f89a4"> Control + O</span> => <span style="background:#6f89a4"> Press enter key</span> => <span style="background:#6f89a4"> Control + X </span>
   
    - Vào thư mục dự án chạy lệnh <strong style="background:#6f89a4"><i>node crawler.js</i></strong> để chạy crawl lần đầu
    - Chạy dự án <strong><i>php artisan serve</i></strong>
2. Setup dự án frontend (<a href="https://github.com/Viet170916/GGS-FE">Git dự án</a>):
    - Clone dự án
    - Cài đặt các gói cần thiết cho dự án <strong style="color:#FFDAC1"><i>yarn install</i></strong> hoặc <strong><i>npm
      i</i></strong>
    - Chạy dự án <strong><i style="color:#FFDAC1">yarn dev</i></strong> hoặc <strong><i>npm run dev</i></strong>
