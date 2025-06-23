// Sidebar expand/collapse and lock button logic

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const hoverZone = document.querySelector('.sidebar-hover-zone');
    const lockBtn = document.getElementById('sidebar-lock-btn');
    const lockIcon = document.getElementById('sidebar-lock-icon');
    let hoverTimeout;
    let isInsideSidebar = false;
    let isLocked = false;

    function updateLockIcon() {
        if (isLocked) {
            lockIcon.setAttribute('icon', 'bx:chevron-left');
        } else {
            lockIcon.setAttribute('icon', 'bx:chevron-right');
        }
    }

    lockBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        isLocked = !isLocked;
        updateLockIcon();
        if (isLocked) {
            sidebar.classList.add('locked');
            sidebar.classList.add('hovering');
        } else {
            sidebar.classList.remove('locked');
            if (!isInsideSidebar) {
                sidebar.classList.remove('hovering');
            }
        }
    });

    // Set initial icon state
    updateLockIcon();

    hoverZone.addEventListener('mouseenter', () => {
        clearTimeout(hoverTimeout);
        sidebar.classList.add('hovering');
        isInsideSidebar = true;
    });
    hoverZone.addEventListener('mouseleave', () => {
        isInsideSidebar = false;
        hoverTimeout = setTimeout(() => {
            if (!isInsideSidebar && !sidebar.classList.contains('locked')) {
                sidebar.classList.remove('hovering');
            }
        }, 200);
    });
    sidebar.addEventListener('mouseenter', () => {
        isInsideSidebar = true;
        clearTimeout(hoverTimeout);
    });
    sidebar.addEventListener('mouseleave', () => {
        isInsideSidebar = false;
        hoverTimeout = setTimeout(() => {
            if (!isInsideSidebar && !sidebar.classList.contains('locked')) {
                sidebar.classList.remove('hovering');
            }
        }, 200);
    });
});
