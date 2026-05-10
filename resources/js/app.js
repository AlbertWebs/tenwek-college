import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('adminShell', () => ({
        sidebarOpen: false,
        sidebarCollapsed: false,
        init() {
            this.sidebarCollapsed = localStorage.getItem('admin.sidebar.collapsed') === '1';
        },
        toggleSidebarCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('admin.sidebar.collapsed', this.sidebarCollapsed ? '1' : '0');
        },
        closeMobileSidebar() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                this.sidebarOpen = false;
            }
        },
    }));

    Alpine.data('socRegisterWizard', () => ({
        step: 1,
        totalSteps: 7,
        next() {
            if (this.step < this.totalSteps) {
                this.step += 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        prev() {
            if (this.step > 1) {
                this.step -= 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        progress() {
            return Math.min(100, Math.round((this.step / this.totalSteps) * 100));
        },
    }));

    Alpine.data('cohsOnCampusWizard', () => ({
        step: 1,
        totalSteps: 7,
        summary: {
            name: '',
            email: '',
            mobile: '',
            programme: '',
            study_mode: '',
            campus: '',
        },
        next() {
            if (!this.validateStep(this.step)) {
                return;
            }
            if (this.step === 6) {
                this.collectSummary();
            }
            if (this.step < this.totalSteps) {
                this.step += 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        prev() {
            if (this.step > 1) {
                this.step -= 1;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        progress() {
            return Math.min(100, Math.round((this.step / this.totalSteps) * 100));
        },
        validateStep(s) {
            const form = this.$refs.appForm;
            if (!form) {
                return true;
            }
            const stepEl = form.querySelector(`[data-step="${s}"]`);
            if (!stepEl) {
                return true;
            }
            const fields = stepEl.querySelectorAll('[required]');
            for (const el of fields) {
                if (el.type === 'file') {
                    if (!el.files || el.files.length === 0) {
                        el.reportValidity();

                        return false;
                    }
                } else if (!el.checkValidity()) {
                    el.reportValidity();

                    return false;
                }
            }

            return true;
        },
        collectSummary() {
            const form = this.$refs.appForm;
            if (!form) {
                return;
            }
            const fd = new FormData(form);
            const fn = (fd.get('first_name') || '').toString().trim();
            const mn = (fd.get('middle_name') || '').toString().trim();
            const ln = (fd.get('last_name') || '').toString().trim();
            this.summary.name = [fn, mn, ln].filter(Boolean).join(' ');
            this.summary.email = (fd.get('email') || '').toString();
            this.summary.mobile = (fd.get('mobile') || '').toString();
            const prog = form.querySelector('[name="programme"]');
            const modeEl = form.querySelector('input[name="study_mode"]:checked');
            const camp = form.querySelector('[name="campus"]');
            this.summary.programme = prog && prog.selectedIndex >= 0 ? (prog.options[prog.selectedIndex]?.text || '').trim() : '';
            this.summary.study_mode =
                modeEl && modeEl.closest('label')
                    ? modeEl.closest('label').textContent.replace(/\s+/g, ' ').trim()
                    : '';
            this.summary.campus = camp && camp.selectedIndex >= 0 ? (camp.options[camp.selectedIndex]?.text || '').trim() : '';
        },
    }));

    /** SOC admin /media: drag-and-drop multi-file queue before POST */
    Alpine.data('socMediaDropzone', () => ({
        dragging: false,
        items: [],
        nextId: 0,
        hint: '',
        maxFiles: 40,
        maxBytes: 12288 * 1024,

        accepts(file) {
            if (this.mimeOk(file.type)) {
                return true;
            }

            return /\.(jpe?g|png|gif|webp|svg|pdf)$/i.test(file.name);
        },

        mimeOk(type) {
            if (!type) {
                return false;
            }

            return (
                type === 'image/jpeg' ||
                type === 'image/png' ||
                type === 'image/gif' ||
                type === 'image/webp' ||
                type === 'image/svg+xml' ||
                type === 'application/pdf'
            );
        },

        addFiles(fileList) {
            this.hint = '';
            if (!fileList?.length) {
                return;
            }
            let skipped = 0;
            for (const file of Array.from(fileList)) {
                if (this.items.length >= this.maxFiles) {
                    skipped += 1;

                    continue;
                }
                if (file.size > this.maxBytes) {
                    skipped += 1;

                    continue;
                }
                if (!this.accepts(file)) {
                    skipped += 1;

                    continue;
                }
                const previewUrl = file.type.startsWith('image/') ? URL.createObjectURL(file) : null;
                this.items.push({ id: this.nextId++, file, previewUrl });
            }
            if (skipped) {
                this.hint = `${skipped} file(s) skipped (40 max, allowed types, or 12MB per file).`;
            }
            this.syncInput();
        },

        remove(id) {
            const item = this.items.find((i) => i.id === id);
            if (item?.previewUrl) {
                URL.revokeObjectURL(item.previewUrl);
            }
            this.items = this.items.filter((i) => i.id !== id);
            this.syncInput();
        },

        clearAll() {
            this.items.forEach((i) => {
                if (i.previewUrl) {
                    URL.revokeObjectURL(i.previewUrl);
                }
            });
            this.items = [];
            this.hint = '';
            this.syncInput();
        },

        syncInput() {
            const input = this.$refs.fileInput;
            if (!input) {
                return;
            }
            const dt = new DataTransfer();
            this.items.forEach((i) => dt.items.add(i.file));
            input.files = dt.files;
        },

        onNativePick(e) {
            this.addFiles(e.target.files);
        },

        formatSize(n) {
            if (n < 1024) {
                return `${n} B`;
            }
            if (n < 1024 * 1024) {
                return `${(n / 1024).toFixed(1)} KB`;
            }

            return `${(n / (1024 * 1024)).toFixed(1)} MB`;
        },
    }));

    /** SOC /gallery: in-page lightbox for grid images */
    Alpine.data('socGallery', (config) => ({
        items: Array.isArray(config?.items) ? config.items : [],
        open: false,
        activeIndex: 0,
        touchStartX: 0,
        init() {
            this.$watch('open', (isOpen) => {
                document.documentElement.classList.toggle('overflow-hidden', Boolean(isOpen));
            });
        },
        onTouchStart(e) {
            if (!e.changedTouches?.length) {
                return;
            }
            this.touchStartX = e.changedTouches[0].screenX;
        },
        onTouchEnd(e) {
            if (!this.open || !this.hasMany || !e.changedTouches?.length) {
                return;
            }
            const dx = e.changedTouches[0].screenX - this.touchStartX;
            if (dx < -48) {
                this.next();
            } else if (dx > 48) {
                this.prev();
            }
        },
        get active() {
            return this.items[this.activeIndex] || { src: '', alt: '', caption: '' };
        },
        get hasMany() {
            return this.items.length > 1;
        },
        openAt(index) {
            if (index < 0 || index >= this.items.length) {
                return;
            }
            this.activeIndex = index;
            this.open = true;
        },
        close() {
            this.open = false;
        },
        next() {
            if (!this.hasMany) {
                return;
            }
            this.activeIndex = (this.activeIndex + 1) % this.items.length;
        },
        prev() {
            if (!this.hasMany) {
                return;
            }
            this.activeIndex = (this.activeIndex - 1 + this.items.length) % this.items.length;
        },
        onKeydown(e) {
            if (!this.open) {
                return;
            }
            if (e.key === 'Escape') {
                e.preventDefault();
                this.close();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.next();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.prev();
            }
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();

function initRevealAnimations() {
    const nodes = document.querySelectorAll('[data-reveal]');
    if (nodes.length === 0) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach((el) => el.classList.add('is-visible'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -6% 0px', threshold: 0.08 },
    );

    nodes.forEach((el) => observer.observe(el));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRevealAnimations);
} else {
    initRevealAnimations();
}

const adminDashboardCharts = document.getElementById('admin-dashboard-charts');
if (adminDashboardCharts) {
    void import('./admin-dashboard-charts.js').then((m) => m.initAdminDashboard(adminDashboardCharts));
}
