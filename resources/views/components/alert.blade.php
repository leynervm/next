<div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastMixin = Swal.mixin({
                toast: true,
                icon: "success",
                title: "Mensaje",
                position: "top-end",
                showConfirmButton: false,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            const message = @json(session('message'));
            let title = (message != undefined || message != null) ? message.title : 'SIN TÍTULO INFORMATIVO';
            let text = (message != undefined || message != null) ? message.text : 'SIN DESCRIPCIÓN';
            let icon = (message != undefined || message != null) ? message.type : 'info';
            const sweet = {
                title: title,
                icon: icon,
                confirmButtonColor: '#0FB9B9',
                confirmButtonText: 'CERRAR',
            };

            if (icon == 'success') {
                sweet.timerProgressBar = true;
                sweet.timer = 3000;
            }
            else {
                // sweet.timer = 7000;
                // sweet.showConfirmButton = true;
            }
            toastMixin.fire(sweet)
        })
    </script>
</div>
