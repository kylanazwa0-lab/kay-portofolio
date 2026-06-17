        <!-- Sidebar -->
        <aside class="wl-sidebar wl-fade-up-1" id="mainSidebar">
            <div class="wl-sidebar-section">
                <div class="wl-sidebar-label">Main Menu</div>
                
                <?php $current_uri = $this->uri->segment(1); ?>
                
                <a href="<?= site_url('dashboard'); ?>" class="wl-sidebar-link <?= ($current_uri == 'dashboard' || $current_uri == '') ? 'active' : ''; ?>" data-tooltip="Dashboard">
                    <i class="fas fa-home icon"></i><span class="wl-sidebar-text"> Dashboard</span>
                </a>
                
                <?php 
                $CI =& get_instance();
                $CI->load->helper('menu');
                $role = $CI->session->userdata('role');
                $menu_items = get_menu_items($role);
                
                if (!empty($menu_items)):
                    foreach ($menu_items as $menu): 
                        $isActive = ($current_uri == $menu['url']) ? 'active' : '';
                ?>
                <a href="<?= site_url($menu['url']); ?>" class="wl-sidebar-link <?= $isActive; ?>" data-tooltip="<?= htmlspecialchars($menu['title']); ?>">
                    <i class="fas <?= $menu['icon']; ?> icon"></i><span class="wl-sidebar-text"> <?= htmlspecialchars($menu['title']); ?></span>
                </a>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
            
            <div class="wl-sidebar-section" style="margin-top: 2rem;">
                <div class="wl-sidebar-label">System</div>
                <a href="<?= site_url('settings'); ?>" class="wl-sidebar-link <?= ($current_uri == 'settings') ? 'active' : ''; ?>" data-tooltip="Settings">
                    <i class="fas fa-cog icon"></i><span class="wl-sidebar-text"> Settings</span>
                </a>
                <a href="javascript:void(0)" class="wl-sidebar-link" style="color: var(--danger);" data-tooltip="Logout" onclick="showLogoutModal()">
                    <i class="fas fa-sign-out-alt icon"></i><span class="wl-sidebar-text"> Logout</span>
                </a>
            </div>
        </aside>

        <!-- Custom Logout Modal -->
        <div id="logoutModal" class="wl-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
            <div class="wl-modal-box" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 2rem; width: 90%; max-width: 400px; text-align: center; transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1.5rem;">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Konfirmasi Logout</h3>
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 2rem;">Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem?</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button type="button" class="wl-btn wl-btn-ghost" onclick="hideLogoutModal()" style="flex: 1;">Batal</button>
                    <a href="<?= site_url('auth/logout'); ?>" class="wl-btn wl-btn-danger" style="flex: 1; display: flex; justify-content: center;">Ya, Logout</a>
                </div>
            </div>
        </div>

        <script>
            function showLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const box = modal.querySelector('.wl-modal-box');
                modal.style.display = 'flex';
                // Trigger reflow
                void modal.offsetWidth;
                modal.style.opacity = '1';
                box.style.transform = 'scale(1)';
            }

            function hideLogoutModal() {
                const modal = document.getElementById('logoutModal');
                const box = modal.querySelector('.wl-modal-box');
                modal.style.opacity = '0';
                box.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }

            // Close on click outside
            document.getElementById('logoutModal').addEventListener('click', function(e) {
                if (e.target === this) hideLogoutModal();
            });
        </script>
