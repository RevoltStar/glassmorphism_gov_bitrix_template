class CookieManager{
    constructor(e={}){
        this.settings={
            containerId:"cookieDiv",
            cookieName:"cookie_consent",
            analyticsName:"analytics_consent",
            pdConsentName:"pd_consent",
            cookieValue:"accepted",
            cookieExpireDays:30,
            checkCookieTimeout:1e3,
            showSettings: false,
            showPdConsent: false,
            ...e
        };
        
        this.settings.cookieDiv = document.getElementById(this.settings.containerId);
        
        document.addEventListener("DOMContentLoaded",()=>{
            setTimeout(()=>this.checkAllConsents(),this.settings.checkCookieTimeout);
        });
    }
    
    setCookie(e,i,o){
        o=new Date(Date.now()+864e5*o).toUTCString();
        document.cookie=`${e}=${i}; expires=${o}; path=/; SameSite=Lax`;
    }
    
    getCookie(e){
        e=document.cookie.match(new RegExp(`(^| )${e}=([^;]+)`));
        return e?decodeURIComponent(e[2]):"";
    }
    
    checkAllConsents(){
        const hasCookieConsent = this.getCookie(this.settings.cookieName) === this.settings.cookieValue;
        const hasAnalyticsConsent = this.getCookie(this.settings.analyticsName) === this.settings.cookieValue;
        
        // Проверяем общее согласие на cookie
        if(!hasCookieConsent){
            this.showBanner();
        } else {
            // Если есть согласие на cookie, проверяем аналитику
            if(hasAnalyticsConsent){
                this.loadAnalytics();
            }
            this.closeCookieDiv();
        }
    }
    
    showBanner(){
        if(this.settings.cookieDiv){
            this.settings.cookieDiv.style.display="flex";
            this.settings.cookieDiv.classList.remove("cookie__hide");
        }
    }
    
    acceptWithOptions(containerId){
        // Проверяем галочки если они есть
        let analyticsAccepted = true;
        let pdAccepted = false;
        
        if(this.settings.showSettings){
            const analyticsCheckbox = document.getElementById(`cookie_analytics_${containerId}`);
            if(analyticsCheckbox){
                analyticsAccepted = analyticsCheckbox.checked;
            }
            
            if(this.settings.showPdConsent){
                const pdCheckbox = document.getElementById(`cookie_pd_${containerId}`);
                if(pdCheckbox){
                    pdAccepted = pdCheckbox.checked;
                }
            }
        }
        
        // Устанавливаем cookie в зависимости от выбора
        this.setCookie(this.settings.cookieName, this.settings.cookieValue, this.settings.cookieExpireDays);
        
        // Аналитика
        this.setCookie(
            this.settings.analyticsName, 
            analyticsAccepted ? this.settings.cookieValue : 'rejected', 
            this.settings.cookieExpireDays
        );
        
        // Согласие на ПДн
        if(this.settings.showPdConsent){
            this.setCookie(
                this.settings.pdConsentName,
                pdAccepted ? this.settings.cookieValue : 'rejected',
                this.settings.cookieExpireDays
            );
        }
        

        if(analyticsAccepted){
            this.loadAnalytics();
        }
        
        this.closeCookieDiv();
    }
    
    rejectCookies(){
        // Пользователь отказался от всего
        this.setCookie(this.settings.cookieName, 'rejected', this.settings.cookieExpireDays);
        this.setCookie(this.settings.analyticsName, 'rejected', this.settings.cookieExpireDays);
        
        if(this.settings.showPdConsent){
            this.setCookie(this.settings.pdConsentName, 'rejected', this.settings.cookieExpireDays);
        }
        
        this.closeCookieDiv();
    }
    
    loadAnalytics(){
		/*console.log("Получено согласие на использование аналитических куков.");*/
    }
    
    closeCookieDiv(){
        this.settings.cookieDiv && (this.settings.cookieDiv.classList.add("cookie__hide"),setTimeout(()=>this.settings.cookieDiv.remove(),1e3));
    }
}
// Глобальная функция для проверки согласий в других местах кода
function checkConsent(consentType) {
    const manager = new CookieManager();
    
    switch(consentType) {
        case 'analytics':
            return manager.getCookie('analytics_consent') === 'accepted';
        case 'pd':
            return manager.getCookie('pd_consent') === 'accepted';
        case 'cookie':
            return manager.getCookie('cookie_consent') === 'accepted';
        default:
            return false;
    }
}

// Пример использования при отправке формы
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-require-pd-consent]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if(!checkConsent('pd')) {
                e.preventDefault();
                alert('Для отправки формы необходимо согласие на обработку персональных данных');
                return false;
            }
        });
    });
});