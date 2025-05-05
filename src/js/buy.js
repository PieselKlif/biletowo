const eventDate = document.getElementById("event-date");
const eventTime = document.getElementById("event-time");

const ticket = {
	'time' : 0,
	'tickets': [],
	'seats': []
};

let price = {};
let selectedPrice = 0;

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
	
	ticket.time = parseInt(eventTime.value);
}

eventTime.addEventListener("change", (e) => {
	ticket.time = parseInt(e.target.value);
	updateSeats(document.getElementById("event-row").value);
});

function updateTickets(sectorid) {
	fetch(`/api/tickets/type/event/${eid}/sector/${sectorid}`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			document.getElementById("ticket-type").innerHTML = "";
			price = data;
			selectedPrice = 0;

			data.forEach((element, i) => {
				const button = document.createElement("button");

				if (i == 0) {
					button.classList.add("selected-ticket");
				}

				button.innerHTML = `${element.name}<br>${element.price}`;
				document.getElementById("ticket-type").appendChild(button);

				button.addEventListener("click", (e) => {
					document.querySelector(".selected-ticket").classList.remove("selected-ticket");
					e.target.classList.add("selected-ticket");

					selectedPrice = i;
				});
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
}

function updateSeats(rowid) {
	fetch(`/api/venue/row/${rowid}/time/${ticket.time}`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			document.getElementById("seat-selector").innerHTML = "";

			data.forEach(element => {
				const button = document.createElement("button");
				button.classList.add("seat");
				button.innerText = element.name;

				if (element.aviable == false){
					button.classList.add("occupied");
					button.tabIndex = -1;
				}

				const seat = ticket.seats.find(seat => seat.id === element.id);

				if (seat) {
					const pr = price.find(price => price.id === seat.type);

					button.style = `background: ${pr.color_hex};`;
					button.classList.add("selected");
				}

				document.getElementById("seat-selector").appendChild(button);

				button.addEventListener("click", (e) => {
					if (e.target.classList.contains("selected")){
						e.target.classList.remove("selected");
						e.target.style = "";

						ticket.seats = ticket.seats.filter(seat => seat.id !== element.id);
					} else {
						e.target.classList.add("selected");
						e.target.style = `background: ${price[selectedPrice].color_hex};`;

						const seat = {
							'id': element.id,
							'type': price[selectedPrice].id
						};

						ticket.seats.push(seat);
					}
				});
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
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

			updateSeats(document.getElementById("event-row").value);

			document.getElementById("event-row").addEventListener("change", (e) => {
				updateSeats(e.target.value);
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
		updateTickets(document.getElementById("event-sector").value);
		
		document.getElementById("event-sector").addEventListener("change", (e) => {
			updateRows(e.target.value);
			updateTickets(e.target.value);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	})