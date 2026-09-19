@if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Toastify({
                        text: "{{ session('success') }}",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#28a745",
                    }).showToast();
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Toastify({
                        text: "{{ session('error') }}",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                    }).showToast();
                });
            </script>
        @endif
