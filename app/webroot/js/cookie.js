setCookie('CakeCookie[here]', document.URL);
function setCookie(cookieName,cookieValue) {
	document.cookie = cookieName+"="+cookieValue
                 + ";path=/";
}