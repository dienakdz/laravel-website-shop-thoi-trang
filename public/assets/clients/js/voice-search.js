$(document).ready(function () {
    const $searchNavItem = $('.search-nav-item');
    const $searchInput = $('#headerSearchInput');
    const $voiceBtn = $('#voiceSearchBtn');

    // 1. Mở ô tìm kiếm khi click nút trigger kính lúp
    $('#searchTriggerBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $searchNavItem.addClass('search-open');
        setTimeout(function () {
            $searchInput.focus();
        }, 120);
    });

    // 2. Đóng ô tìm kiếm khi click nút X
    $('#closeSearchBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeSearch();
    });

    // 3. Đóng khi click bất kỳ đâu ra ngoài ô tìm kiếm
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-nav-item').length) {
            closeSearch();
        }
    });

    // 4. Đóng khi nhấn phím ESC
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            closeSearch();
        }
    });

    // 5. Ngăn đóng khi click bên trong khung tìm kiếm
    $('#headerSearchBox').on('click', function (e) {
        e.stopPropagation();
    });

    function closeSearch() {
        $searchNavItem.removeClass('search-open');
        if (recognition && isListening) {
            try {
                recognition.stop();
            } catch (err) {}
        }
    }

    // 6. Nhận dạng giọng nói (Web Speech API)
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition = null;
    let isListening = false;

    if (SpeechRecognition) {
        recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.lang = 'vi-VN';
        recognition.interimResults = false;

        recognition.onstart = function () {
            isListening = true;
            $voiceBtn.addClass('listening');
            $voiceBtn.find('i').removeClass('fa-microphone').addClass('fa-microphone-slash');
            $voiceBtn.attr('title', 'Đang nghe... Bấm để dừng');
            $searchInput.attr('placeholder', 'Đang nghe bạn nói...');
        };

        recognition.onresult = function (event) {
            if (event.results && event.results.length > 0) {
                const transcript = event.results[0][0].transcript;
                $searchInput.val(transcript);
                $searchInput.attr('placeholder', 'Tìm sản phẩm...');

                // Tự động submit sau 500ms
                setTimeout(function () {
                    $('#headerSearchForm').submit();
                }, 500);
            }
        };

        recognition.onerror = function (event) {
            console.warn('Speech recognition error:', event.error);
            resetVoiceState();
        };

        recognition.onend = function () {
            resetVoiceState();
        };

        function resetVoiceState() {
            isListening = false;
            $voiceBtn.removeClass('listening');
            $voiceBtn.find('i').removeClass('fa-microphone-slash').addClass('fa-microphone');
            $voiceBtn.attr('title', 'Tìm bằng giọng nói');
            $searchInput.attr('placeholder', 'Tìm sản phẩm...');
        }

        $voiceBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!isListening) {
                try {
                    recognition.start();
                } catch (err) {
                    console.warn(err);
                }
            } else {
                recognition.stop();
            }
        });
    } else {
        // Trình duyệt không hỗ trợ Web Speech API -> ẩn nút microphone
        $voiceBtn.hide();
    }
});
