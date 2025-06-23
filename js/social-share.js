const fbBtn = document.querySelector(".fb-btn");
const waBtn = document.querySelector(".wa-btn");
const twBtn = document.querySelector(".tw-btn");
const liBtn = document.querySelector(".li-btn");

const pageUrl = location.href
const message = "hi every one ,please check  out"


function init(){
	
	let postUrl=encodeURI(document.location.href);
	let postTitle=encodeURI("hi every one ,please check this out: ");

	fbBtn.setAttribute("href",`https://www.facebook.com/sharer.php?u=${postUrl}`);
	waBtn.setAttribute("href",`https://api.whatsapp.com/send?text=${message} , ${pageUrl}`);
	twBtn.setAttribute("href",`https://twitter.com/share?url=${postUrl}&text=${postTitle}&via=[via]&hashtags=[hashtags]`);
	liBtn.setAttribute("href",`https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`);
}
init();