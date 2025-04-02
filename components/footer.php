</main>

    <!-- Toast Notification -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function showToast(type, message) {
            let icon, title;
            if (type === 'success') {
                icon = 'success';
                title = 'Berhasil';
            } else if (type === 'error') {
                icon = 'error';
                title = 'Gagal';
            } else {
                icon = type;
                title = message;
                return;
            }
            Toast.fire({
                icon: icon,
                title: title + (message ? ': ' + message : '')
            });
        }

        function showConfirm(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4b49ac',
                cancelButtonColor: '#f3797e',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        function showSuccess(title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'success',
                confirmButtonColor: '#4b49ac'
            });
        }
    </script>
</body>
</html>