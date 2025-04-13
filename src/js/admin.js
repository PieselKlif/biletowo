const dir = "/admin/pages/";

let site = "home";

function toggleSidebar() {
	document.querySelector('.sidebar').classList.toggle('collapsed');
}

function loadSite(site) {
	document.querySelector("main").classList = "";
	document.querySelector("main").classList.add(site);
	document.querySelector("main").innerHTML = "Ładowanie zawartości...";

	fetch(`${dir}${site}.php`)
		.then((res) => res.text())
		.then((content => {
			document.querySelector("main").innerHTML = content;
		}));
}

loadSite(site);

document.querySelectorAll("li a").forEach((element) => {
	element.addEventListener("click", (e) => {
		document.querySelector("li a.active").classList.remove("active");

		element.classList.add("active");

		site = element.getAttribute("data-target");
		loadSite(site);
	});
});

function removeImg(name) {
	fetch(`removeimg.php?name=${name}`)
	location.reload();
}