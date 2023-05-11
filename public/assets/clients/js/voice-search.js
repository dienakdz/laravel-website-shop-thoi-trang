// Kiểm tra trình duyệt có hỗ trợ nhận dạng giọng nói không
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

if (SpeechRecognition) {
    console.log("Your browser supports speech recognition");

    // Khởi tạo đối tượng nhận dạng giọng nói và thiết lập các thuộc tính
    const recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.lang = "vi-VN";

    // Xử lý kết quả nhận dạng giọng nói và đưa vào ô tìm kiếm
    recognition.addEventListener("result", (event) => {
        const transcript = event.results[0][0].transcript;
        $('.input-search').val(transcript);

        // Đợi 750ms sau đó submit form tìm kiếm
        setTimeout(() => {
            $(event.target).closest('form').submit();
        }, 750)
    });

    // Bắt sự kiện khi người dùng nhấn vào biểu tượng microphone
    $('.icons').on('click', '.fa-microphone', function() {
        $(this).removeClass('fa-microphone');
        $(this).addClass('fa-microphone-slash');

        // Bắt đầu nhận dạng giọng nói
        recognition.start();
    });

    // Bắt sự kiện khi người dùng nhấn vào biểu tượng microphone-slash
    $('.icons').on('click', '.fa-microphone-slash', function() {
        $(this).removeClass('fa-microphone-slash');
        $(this).addClass('fa-microphone');

        // Dừng nhận dạng giọng nói
        recognition.stop();
    });

    // Bắt sự kiện khi người dùng nhấn vào biểu tượng xóa
    $('.close-icon').click(function() {
        $(this).parent().removeClass('search-open');
        $('.input-search').val('');
        $('li.person').show();

        // Dừng nhận dạng giọng nói
        recognition.stop();
    });
} else {
    console.log("Your browser does not support speech recognition");
}

// Bắt sự kiện khi người dùng nhấn vào biểu tượng tìm kiếm
$('.search-icon').click(function() {
    $(this).parent().addClass('search-open');
    $('li.person').hide();
});

// Bắt sự kiện khi người dùng nhấn vào biểu tượng submit form tìm kiếm
$('.new-search-icon').click(function() {
    $(this).closest('form').submit();
});