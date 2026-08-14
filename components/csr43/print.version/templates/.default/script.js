// Обработка параметра print=Y
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const printParam = urlParams.get('print');
    
    if (printParam === 'Y') {
		document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        	img.loading = 'eager';
    	});
		/*
        // Добавляем стили для печати
        const printStyles = `
            @media print {
                .header, .footer, .sidebar, .breadcrumbs, 
                .navigation, .print-version-btn, .no-print {
                    display: none !important;
                }
                body {
                    font-size: 12pt;
                    line-height: 1.4;
                }
                .container {
                    width: 100% !important;
                }
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = printStyles;
        document.head.appendChild(styleSheet);
		*/
        // Автоматически открываем диалог печати
        setTimeout(function() {
            window.print();
        }, 500);
    }
});