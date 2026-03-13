<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Ảnh & File TXT</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; max-width: 600px; margin: auto; }
        .upload-section { border: 2px dashed #ccc; padding: 20px; text-align: center; border-radius: 10px; margin-bottom: 20px; }
        #preview-container { margin-top: 20px; border: 1px solid #eee; padding: 10px; display: none; }
        img { max-width: 100%; height: auto; border-radius: 5px; }
        pre { background: #f4f4f4; padding: 10px; white-space: pre-wrap; word-wrap: break-word; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<h2>Tải tệp lên hệ thống</h2>

<div class="upload-section">
    <input type="file" id="fileInput" accept="image/*, .txt" hidden>
    <button onclick="document.getElementById('fileInput').click()">Chọn file (Ảnh hoặc .txt)</button>
    <p id="file-info">Chưa có file nào được chọn</p>
</div>

<div id="preview-container">
    <h4>Xem trước nội dung:</h4>
    <div id="content-display"></div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('file-info');
    const previewContainer = document.getElementById('preview-container');
    const contentDisplay = document.getElementById('content-display');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        uploadFile(file);

        fileInfo.innerText = `File đã chọn: ${file.name}`;
        previewContainer.style.display = 'block';
        contentDisplay.innerHTML = ''; // Xóa nội dung cũ

        const reader = new FileReader();

        // Xử lý nếu là ẢNH
        if (file.type.startsWith('image/')) {
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                contentDisplay.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
        // Xử lý nếu là file TXT
        else if (file.type === 'text/plain') {
            reader.onload = function(e) {
                const pre = document.createElement('pre');
                pre.textContent = e.target.result;
                contentDisplay.appendChild(pre);
            };
            reader.readAsText(file);
        } else {
            contentDisplay.innerHTML = '<p style="color:red">Định dạng file không được hỗ trợ để xem trước!</p>';
        }
    });
    
    function uploadFile(file) {
        const formData = new FormData();
        formData.append('file_upload', file);

        fetch('/file/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Upload thành công:', data);
        })
        .catch(error => {
            console.error('Lỗi khi upload:', error);
        });
    }
</script>

</body>
</html>