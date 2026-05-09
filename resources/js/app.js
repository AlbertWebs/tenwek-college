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
