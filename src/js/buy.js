const eventDate = document.getElementById("event-date");
const eventTime = document.getElementById("event-time");

const ticket = {
	'tickets': [],
	'seats': []
};

function updateTime(selectedEvent) {
	eventTime.innerHTML = "";
	if (selectedEvent) {
		selectedEvent.event_times.forEach(time => {
			const option = document.createElement("option");
			option.value = time.id;
			option.innerText = time.time;
			eventTime.appendChild(option);
		});
	}
}

function updateRows(sectorid) {
	fetch(`/api/venue/sector/${sectorid}/rows`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			document.getElementById("event-row").innerHTML = "";

			data.forEach(element => {
				const option = document.createElement("option");
				option.innerText = element.name;
				option.value = element.id;

				document.getElementById("event-row").appendChild(option);
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
}

fetch(`/api/event/${eid}/time`)
	.then(response => {
		if (!response.ok) {
			return response.json().then(errorData => {
					throw new Error(errorData.error);
			});
		}
		return response.json();
	})
	.then(data => {
		data.forEach(element => {
			const option = document.createElement("option");
			const dateParts = element.event_date.split("-");
			option.value = element.event_date;
			option.innerText = `${dateParts[2]}.${dateParts[1]}.${dateParts[0]}`;
			eventDate.appendChild(option);
		});

		const selectedDate = eventDate.value;
		const selectedEvent = data.find(event => event.event_date === selectedDate);

		updateTime(selectedEvent);

		eventDate.addEventListener("change", (e) => {
			const selectedDate = e.target.value;
			const selectedEvent = data.find(event => event.event_date === selectedDate);

			updateTime(selectedEvent);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	});

fetch(`http://localhost:3000/api/event/${eid}/tickets`)
	.then(response => {
		if (!response.ok) {
			return response.json().then(errorData => {
					throw new Error(errorData.error);
			});
		}
		return response.json();
	})
	.then(data => {
		data.tickets.forEach(element => {
			const tic = {"id": element.id, "quantity": 0};

			ticket.tickets.push(tic);

			const mainDiv = document.createElement("div");
			const name = document.createElement("h2");
			const sDiv = document.createElement("div");
			const price = document.createElement("h3");
			const minus = document.createElement("button");
			const num = document.createElement("span");
			const add = document.createElement("button");

			mainDiv.classList.add("ticket");
			minus.classList.add("minus");
			num.classList.add("num");
			add.classList.add("add");
			name.innerText = element.name;
			price.innerText = `${element.price} zł`;
			minus.innerText = "-";
			num.innerText = "0";
			add.innerText = "+";
			sDiv.appendChild(price);
			sDiv.appendChild(minus);
			sDiv.appendChild(num);
			sDiv.appendChild(add);
			mainDiv.appendChild(name);
			mainDiv.appendChild(sDiv);
			document.getElementById("tickets").appendChild(mainDiv);

			add.addEventListener("click", () => {
				const ticketU = ticket.tickets.find(ticket => ticket.id === element.id);

				if (ticketU) {
					ticketU.quantity += 1;

					num.innerText = ticketU.quantity;
				}
			});

			minus.addEventListener("click", () => {
				const ticketU = ticket.tickets.find(ticket => ticket.id === element.id);

				if (ticketU && ticketU.quantity > 0) {
					ticketU.quantity -= 1;

					num.innerText = ticketU.quantity;
				}
			});
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	})
	
fetch(`/api/venue/${vid}/sectors`)
	.then(response => {
		if (!response.ok) {
			return response.json().then(errorData => {
					throw new Error(errorData.error);
			});
		}
		return response.json();
	})
	.then(data => {
		data.forEach(element => {
			const option = document.createElement("option");
			option.innerText = element.name;
			option.value = element.id;
			document.getElementById("event-sector").appendChild(option);

		});

		updateRows(document.getElementById("event-sector").value);
		
		document.getElementById("event-sector").addEventListener("change", (e) => {
			updateRows(e.target.value);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	})