// Système de toasts
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;max-width:400px;';
        document.body.appendChild(container);
    }
    
    const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
    const colors = { success: '#28a745', error: '#dc3545', warning: '#ffc107', info: '#17a2b8' };
    
    const toast = document.createElement('div');
    toast.style.cssText = `background:white;padding:15px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3);border-left:4px solid ${colors[type]};display:flex;gap:12px;align-items:center;transform:translateX(450px);opacity:0;transition:all 0.3s ease;`;
    toast.innerHTML = `
        <span style="font-size:24px;">${icons[type]}</span>
        <span style="flex:1;color:#333;font-size:14px;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;font-size:24px;color:#999;cursor:pointer;padding:0;width:24px;height:24px;">×</button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(450px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Flag pour éviter le double affichage
let toastsDisplayed = false;

// Auto-affichage au chargement
document.addEventListener('DOMContentLoaded', function() {
    if (toastsDisplayed) return; // Déjà affiché, on arrête
    toastsDisplayed = true;
    
    if (typeof toastMessages !== 'undefined' && toastMessages.length > 0) {
        toastMessages.forEach(msg => showToast(msg.message, msg.type));
    }
});