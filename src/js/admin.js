function toggleSidebar() {
	document.querySelector('.sidebar').classList.toggle('collapsed');
}

const dir = "/admin/pages/";

function loadSite(site) {
	fetch(`${dir}${site}.php`)
		.then((res) => res.text())
		.then((content => {
			document.querySelector("main").innerHTML = content;
		}));
}

loadSite("home");

document.querySelectorAll("li a").forEach((element) => {
	element.addEventListener("click", (e) => {
		document.querySelector("li a.active").classList.remove("active");

		element.classList.add("active");

		loadSite(element.getAttribute("data-target"));
	});
});