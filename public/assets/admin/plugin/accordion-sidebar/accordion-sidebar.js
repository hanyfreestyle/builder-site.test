document.addEventListener('alpine:init', () => {
    Alpine.nextTick(() => {
        const sidebar = document.querySelector('.fi-sidebar');

        sidebar.addEventListener('click', (event) => {
            const button = event.target.closest(
                '.fi-sidebar-group-button, .fi-sidebar-group-collapse-button'
            );

            if (!button) return;

            const group = button.closest('.fi-sidebar-group');
            const label = group?.dataset?.groupLabel;

            if (!label) return;

            event.stopPropagation();
            event.preventDefault();

            const sidebarStore = Alpine.store('sidebar');
            const isCollapsed = sidebarStore.groupIsCollapsed(label);

            // إغلاق جميع المجموعات الأخرى
            document.querySelectorAll('.fi-sidebar-group').forEach(otherGroup => {
                const otherLabel = otherGroup.dataset.groupLabel;
                if (otherLabel && otherLabel !== label) {
                    sidebarStore.collapseGroup(otherLabel);
                }
            });

            // فتح المجموعة الحالية إذا كانت مغلقة
            if (isCollapsed) {
                sidebarStore.expandGroup(label); // أو toggleCollapsedGroup حسب ما يدعمه Alpine Store
            }
        });
    });
});
