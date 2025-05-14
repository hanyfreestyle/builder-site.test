@if($isactive)
    <script>
        function toggleTheme() {
            const body = document.documentElement;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            const logo = document.getElementById('navbar-logo');
            const themeIcon = document.getElementById('themeIcon');
            const themeIconMobile = document.getElementById('themeIconMobile');


            // تغيير السمة على الصفحة
            body.setAttribute('data-theme', newTheme);

            // تحديث الشعار بناءً على السمة الجديدة
            if (newTheme === 'dark') {
                logo.src = "{{ getDefPhotoPath($DefPhotoList, 'logo_dark') }}"; // شعار الوضع المظلم
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun'); // أيقونة الشمس
                themeIconMobile.classList.remove('fa-moon');
                themeIconMobile.classList.add('fa-sun'); // أيقونة الشمس
            } else {
                logo.src = "{{ getDefPhotoPath($DefPhotoList, 'logo_light') }}"; // شعار الوضع المضيء
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon'); // أيقونة القمر
                themeIconMobile.classList.remove('fa-sun');
                themeIconMobile.classList.add('fa-moon'); // أيقونة القمر
            }

            // إرسال السمة إلى الخادم لتحديث الجلسة
            fetch('{{route('web.setWebTheme')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ theme: newTheme })
            }).catch((error) => {
                console.error('Error updating theme:', error);
            });
        }

        // عند تحميل الصفحة، قراءة السمة المحفوظة وتطبيقها
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = "{{ session('theme', 'light') }}";
            const body = document.documentElement;
            const logo = document.getElementById('navbar-logo');
            const themeIcon = document.getElementById('themeIcon');
            const themeIconMobile = document.getElementById('themeIconMobile');
            // ضبط السمة
            body.setAttribute('data-theme', savedTheme);

            // ضبط الشعار والأيقونة
            if (savedTheme === 'dark') {
                logo.src = "{{ getDefPhotoPath($DefPhotoList, 'logo_dark') }}"; // شعار الوضع المظلم
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun'); // أيقونة الشمس
                themeIconMobile.classList.remove('fa-moon');
                themeIconMobile.classList.add('fa-sun'); // أيقونة الشمس
            } else {
                logo.src = "{{ getDefPhotoPath($DefPhotoList, 'logo_light') }}"; // شعار الوضع المضيء
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon'); // أيقونة القمر
                themeIconMobile.classList.remove('fa-sun');
                themeIconMobile.classList.add('fa-moon'); // أيقونة القمر
            }
        });


    </script>
@endif

