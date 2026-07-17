class CookieManager{
    constructor(e={}){
        this.settings={
            containerId:"cookieDiv",
            cookieName:"cookie_consent",
            analyticsName:"analytics_consent",
            cookieValue:"accepted",
            cookieExpireDays:30,
            checkCookieTimeout:1e3,
            showSettings: false,
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
    	const cookieValue = this.getCookie(this.settings.cookieName);
    
    	if(cookieValue === ""){
        	this.showBanner();
        	return;
    	}

    	if(cookieValue === this.settings.cookieValue){
        	if(this.getCookie(this.settings.analyticsName) === this.settings.cookieValue){
            	this.loadAnalytics();
        	}
    	}

    	this.closeCookieDiv();
	}

    showBanner(){
        if(this.settings.cookieDiv){
            this.settings.cookieDiv.style.display="flex";
            this.settings.cookieDiv.classList.remove("cookie__hide");
        }
    }
    
    acceptWithOptions(containerId){
        let analyticsAccepted = true;
        
        if(this.settings.showSettings){
            const analyticsCheckbox = document.getElementById(`cookie_analytics_${containerId}`);
            if(analyticsCheckbox){
                analyticsAccepted = analyticsCheckbox.checked;
            }
        }
        
        this.setCookie(this.settings.cookieName, this.settings.cookieValue, this.settings.cookieExpireDays);
        
        this.setCookie(
            this.settings.analyticsName, 
            analyticsAccepted ? this.settings.cookieValue : 'rejected', 
            this.settings.cookieExpireDays
        );
        
        if(analyticsAccepted){
            this.loadAnalytics();
        }
        
        this.closeCookieDiv();
    }
    
    rejectCookies(){
        this.setCookie(this.settings.cookieName, 'rejected', this.settings.cookieExpireDays);
        this.setCookie(this.settings.analyticsName, 'rejected', this.settings.cookieExpireDays);
        
        this.closeCookieDiv();
    }
    
    loadAnalytics(){
		const script = document.createElement('script');
		script.src = '/local/metrika.js';
  		script.async = true;
  		document.head.appendChild(script);
    }
    
    closeCookieDiv(){
        this.settings.cookieDiv && (this.settings.cookieDiv.classList.add("cookie__hide"),setTimeout(()=>this.settings.cookieDiv.remove(),1e3));
    }
}

function checkConsent(consentType) {
    const manager = new CookieManager();
    
    switch(consentType) {
        case 'analytics':
            return manager.getCookie('analytics_consent') === 'accepted';
        case 'cookie':
            return manager.getCookie('cookie_consent') === 'accepted';
        default:
            return false;
    }
}