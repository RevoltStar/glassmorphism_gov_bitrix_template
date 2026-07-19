class CookieManager {
    constructor(settings = {}) {
        this.settings = {
            containerId: 'cookieDiv',
            cookieName: 'cookie_consent',
            analyticsName: 'analytics_consent',
            cookieValue: 'accepted',
            rejectedValue: 'rejected',
            cookieExpireDays: 30,
            checkCookieTimeout: 1000,
            showSettings: false,
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

    checkAllConsents() {
        const decision = getConsentCookie(this.settings.cookieName);

        if (
            decision !== this.settings.cookieValue
            && decision !== this.settings.rejectedValue
        ) {
            this.showBanner();
            return;
        }

        if (
            decision === this.settings.cookieValue
            && getConsentCookie(this.settings.analyticsName) === this.settings.cookieValue
        ) {
            this.loadAnalytics();
        }

        this.closeCookieDiv();
    }

    showBanner() {
        if (!this.settings.cookieDiv) {
            return;
        }

        this.settings.cookieDiv.classList.remove('cookie__hide');
    }

    acceptWithOptions() {
        let analyticsAccepted = !this.settings.showSettings;

        if (this.settings.showSettings) {
            const analyticsCheckbox = document.getElementById(
                `cookie_analytics_${this.settings.containerId}`
            );
            analyticsAccepted = Boolean(analyticsCheckbox && analyticsCheckbox.checked);
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

        if (analyticsAccepted) {
            this.loadAnalytics();
        }

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

    closeCookieDiv() {
        if (!this.settings.cookieDiv) {
            return;
        }

        this.settings.cookieDiv.classList.add('cookie__hide');
        window.setTimeout(() => this.settings.cookieDiv.remove(), 1000);
    }
}

function getConsentCookie(name) {
    if (typeof name !== 'string' || name === '') {
        return '';
    }

    const escapedName = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const match = document.cookie.match(new RegExp(`(?:^|;\\s*)${escapedName}=([^;]*)`));

    if (!match) {
        return '';
    }

    try {
        return decodeURIComponent(match[1]);
    } catch (error) {
        return '';
    }
}
