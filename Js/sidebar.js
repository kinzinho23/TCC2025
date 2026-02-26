(function () {
            // Funções de controle da sidebar com checagens e eventos úteis
            function getSidebar() { return document.getElementById('sidebar'); }

            function openSidebar() {
                var s = getSidebar();
                if (!s) return;
                s.classList.add('open');
                s.setAttribute('aria-hidden', 'false');
            }

            function closeSidebar() {
                var s = getSidebar();
                if (!s) return;
                s.classList.remove('open');
                s.setAttribute('aria-hidden', 'true');
            }

            window.openSidebar = openSidebar;
            window.closeSidebar = closeSidebar;
            window.toggleSidebar = toggleSidebar;
        })();