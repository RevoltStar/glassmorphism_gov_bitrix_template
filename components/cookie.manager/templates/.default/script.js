class CookieManager {
    constructor(settings = {}) {
        this.settings = {
            containerId: 'cookieDiv',
            cookieName: 'cookie_consent',
            analyticsName: 'analytics_consent',
            pdConsentName: 'pd_consent',
            cookieValue: 'accepted',
            rejectedValue: 'rejected',
            cookieExpireDays: 30,
            checkCookieTimeout: 1000,
            showSettings: false,
            showPdConsent: false,
            analyticsScriptSrc: '',
            ...settings
        };

        this.settings.cookieDiv = document.getElementById(this.settings.containerId);

        const initialize = () => {
            window.setTimeout(
                () => this.checkAllConsents(),
                this.settings.checkCookieTimeout
            );
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialize, {once: true});
        } else {
            initialize();
        }
    }

    setCookie(name, value, expireDays) {
        const expires = new Date(Date.now() + 86400000 * expireDays).toUTCString();
        const secure = window.location.protocol === 'https:' ? '; Secure' : '';

        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax${secure}`;
    }

    getCookie(name) {
        const escapedName = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const match = document.cookie.match(new RegExp(`(?:^|; )${escapedName}=([^;]*)`));

        return match ? decodeURIComponent(match[1]) : '';
    }

    checkAllConsents() {
        const decision = this.getCookie(this.settings.cookieName);

        if (decision === '') {
            this.showBanner();
            return;
        }

        if (
            decision === this.settings.cookieValue
            && this.getCookie(this.settings.analyticsName) === this.settings.cookieValue
        ) {
            this.loadAnalytics();
        }

        this.closeCookieDiv();
    }

    showBanner() {
        if (!this.settings.cookieDiv) {
            return;
        }

        this.settings.cookieDiv.style.display = 'flex';
        this.settings.cookieDiv.classList.remove('cookie__hide');
    }

    acceptWithOptions(containerId) {
        let analyticsAccepted = !this.settings.showSettings;
        let pdAccepted = false;

        if (this.settings.showSettings) {
            const analyticsCheckbox = document.getElementById(`cookie_analytics_${containerId}`);
            analyticsAccepted = Boolean(analyticsCheckbox && analyticsCheckbox.checked);
        }

        if (this.settings.showPdConsent) {
            const pdCheckbox = document.getElementById(`cookie_pd_${containerId}`);
            pdAccepted = Boolean(pdCheckbox && pdCheckbox.checked);
        }

        this.setCookie(
            this.settings.cookieName,
            this.settings.cookieValue,
            this.settings.cookieExpireDays
        );
        this.setCookie(
            this.settings.analyticsName,
            analyticsAccepted ? this.settings.cookieValue : this.settings.rejectedValue,
            this.settings.cookieExpireDays
        );

        if (this.settings.showPdConsent) {
            this.setCookie(
                this.settings.pdConsentName,
                pdAccepted ? this.settings.cookieValue : this.settings.rejectedValue,
                this.settings.cookieExpireDays
            );
        }

        if (analyticsAccepted) {
            this.loadAnalytics();
        }

        this.dispatchConsentChange();
        this.closeCookieDiv();
    }

    rejectCookies() {
        this.setCookie(
            this.settings.cookieName,
            this.settings.rejectedValue,
            this.settings.cookieExpireDays
        );
        this.setCookie(
            this.settings.analyticsName,
            this.settings.rejectedValue,
            this.settings.cookieExpireDays
        );

        if (this.settings.showPdConsent) {
            this.setCookie(
                this.settings.pdConsentName,
                this.settings.rejectedValue,
                this.settings.cookieExpireDays
            );
        }

        this.dispatchConsentChange();
        this.closeCookieDiv();
    }

    loadAnalytics() {
        if (
            !this.settings.analyticsScriptSrc
            || document.getElementById('site-analytics-script')
        ) {
            return;
        }

        const script = document.createElement('script');
        script.id = 'site-analytics-script';
        script.src = this.settings.analyticsScriptSrc;
        script.async = true;
        document.head.appendChild(script);
    }

    dispatchConsentChange() {
        document.dispatchEvent(new CustomEvent('cookieconsentchange', {
            detail: {
                cookie: checkConsent('cookie'),
                analytics: checkConsent('analytics'),
                pd: checkConsent('pd')
            }
        }));
    }

    closeCookieDiv() {
        if (!this.settings.cookieDiv) {
            return;
        }

        this.settings.cookieDiv.classList.add('cookie__hide');
        window.setTimeout(() => this.settings.cookieDiv.remove(), 1000);
    }
}

function getConsentCookie(name) {
    const escapedName = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const match = document.cookie.match(new RegExp(`(?:^|; )${escapedName}=([^;]*)`));

    return match ? decodeURIComponent(match[1]) : '';
}

function checkConsent(consentType) {
    const cookieNames = {
        cookie: 'cookie_consent',
        analytics: 'analytics_consent',
        pd: 'pd_consent'
    };
    const cookieName = cookieNames[consentType];

    return cookieName
        ? getConsentCookie(cookieName) === 'accepted'
        : false;
}

function bindPdConsentForms() {
    document.querySelectorAll('form[data-require-pd-consent]').forEach((form) => {
        if (form.dataset.pdConsentBound === 'true') {
            return;
        }

        form.dataset.pdConsentBound = 'true';
        form.addEventListener('submit', (event) => {
            if (checkConsent('pd')) {
                return;
            }

            event.preventDefault();
            window.alert('Для отправки формы необходимо согласие на обработку персональных данных.');
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindPdConsentForms, {once: true});
} else {
    bindPdConsentForms();
}
